const express = require('express');
const router = express.Router();
const checkAuth =  require('../middlewares/check-auth');
const  { authorize } = require('../middlewares/auth');
const ROLES = require("../config/roles_list");


const OrdersController = require('../controllers/orders');

//Handle incoming GET requests to /ordes
router.get('/', checkAuth, authorize([ROLES.Admin, ROLES.Employee]), 
    OrdersController.orders_get_all);

//Handle incoming POST requests to /orders
router.post('/', checkAuth, OrdersController.orders_create_order);

//Handle incoming GET requests to /ordes/id
router.get('/:orderId', checkAuth, 
    OrdersController.orders_get_order_by_id);

//Handle incoming Delete requests to /ordes
router.delete('/:orderId', authorize([ROLES.Admin, ROLES.Employee]),
    checkAuth, OrdersController.orders_delete_order_by_id);

module.exports = router;