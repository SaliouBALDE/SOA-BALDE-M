const express = require('express');
const router = express.Router();

const UsersController = require("../controllers/users");
const checkAuth = require('../middlewares/check-auth');
const  { authorize } = require('../middlewares/auth');
const ROLES = require("../config/roles_list");

//Fonctionality to create user
router.post('/signup', UsersController.users_user_signup);

//Fonctionality to login user
router.post('/login', UsersController.users_user_login);

//Fonctionality to delete user
router.delete('/:userId', checkAuth, authorize([ROLES.Admin]), 
    UsersController.users_user_delete_by_id);

//Update user
router.patch('/:userId', checkAuth, authorize([ROLES.Admin]), 
    UsersController.users_update_user_by_id);

module.exports = router;