const mongoose = require('mongoose');
const bcrypt = require('bcrypt');

const User =  require('../models/user');
const AuthJWT = require('../helpers/jwt')

exports.users_user_signup = (req, res, next) => {
    User.find({ email: req.body.email })
    .exec()
    .then(user => {
        if (user.length >= 1) {
            return res.status(409).json({
                message: 'This mail already exists'
            });
        } else {
            bcrypt.hash(req.body.password, 10, (err, hash) => {
                if (err) {
                    return res.status(500).json({
                        message: 'The password is not safelly hashed!',
                        error: err
                    });
                } else {
                    // Create an store new user
                    const user = new User({
                        _id: new mongoose.Types.ObjectId(),
                        roles: req.body.roles || { "Client": 2001},
                        email: req.body.email,
                        password: hash
                    }); 
                    user
                    .save()
                    .then(result => {
                        console.log(result);
                        res.status(200).json({
                            message: "User succefully created!"
                        });
                    })
                    .catch(err => {
                        console.log(err);
                        res.status(500).json({
                        message: "User could not be stored correctly...",
                        error: err
                        });
                    });
                }
            })
        }
    })   
}

exports.users_user_login = (req, res, next) => {
    User.find({ email: req.body.email})
    .exec()
    .then(user => {
        if (user.length < 1) {
            return res.status(401).json({
                message: 'Authorization failed!'
            });
        }
        bcrypt.compare(req.body.password, user[0].password, (err, result) => {
            if (err) {
                return res.status(401).json({
                    message: 'Authorization failed!'
                });
            }
            if (result) {
                //Create token
                const token = AuthJWT.createToken({
                    userId: user[0]._id,
                    roles: user[0].roles,
                    email: user[0].email
                }, 
                process.env.JWT_KEY, 
                {
                    expiresIN: '1h'
                });
                return res.status(200).json({
                    message: 'Authentification successful!',
                    token: token
                });
            }
            return res.status(401).json({
                message: 'Authorization failed!'
            });
        });
    })
    .catch(err => {
        console.log(err);
        res.status(500).json({
        message: "User could not login correctly...",
        error: err
        });
    });
}

exports.users_user_delete_by_id = (req, res, next) => {
    User.deleteOne({ _id: req.params.userId})
    .exec()
    .then(result => {
        res.status(200).json({
            message: "User deleted!"
        });
    })
    .catch(err => {
        console.log(err);
        res.status(500).json({
        message: "User could not be correctly deleted",
        error: err
        });
    })
}

exports.users_update_user_by_id = (req, res, next) => {
    const id = req.params.userId;
    const updateOps = {};
    for (const ops of req.body) {  //For the update we change one attribute at time
        updateOps[ops.propName] = ops.value;
    }
    User.updateOne({ _id: id }, {$set: updateOps })
    .exec()
    .then(result => {
        res.status(200).json({
            message: 'User Updated'
        });
    })
    .catch( err => {
        console.log(err);
        res.status(500).json({
            error: err
        });
    });
}