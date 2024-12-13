const express = require('express');
const router = express.Router();
const mongoose = require('mongoose');
const bcrypt = require('bcrypt');

const User =  require('../models/user');

//Fonctionality to creat user
router.post('/signup', (req, res, next) => {
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
});
//Fonctionality to log in user
router.post('/login', (req, res, next) => {
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
                return res.status(200).json({
                    message: 'Authentification successful!'
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
})
//Fonctionality de delete user
router.delete('/:userId', (req, res, next) => {
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
})

module.exports = router;