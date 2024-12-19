const mongoose = require('mongoose');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

const User =  require('../models/user');

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
                    const user = new User({
                        _id: new mongoose.Types.ObjectId(),
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
                const token = jwt.sign({
                    email: user[0].email, //The email
                    userId: user[0]._id//The user id
                }, 
                process.env.JWT_KEY, //The jwt secret key
                {
                    expiresIn: "1h" //Time for the token to expire
                },
                );
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