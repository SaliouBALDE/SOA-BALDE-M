const express = require('express');
const router = express.Router();

const UsersController = require("../controllers/users");
const checkAuth = require('../middlewares/check-auth');

//Fonctionality to create user
router.post('/signup', UsersController.users_user_signup);

//Fonctionality to login user
router.post('/login', UsersController.users_user_login);

//Fonctionality de delete user
router.delete('/:userId', checkAuth, UsersController.users_user_delete_by_id);

module.exports = router;