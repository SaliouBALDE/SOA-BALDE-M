const express = require('express');
const router = express.Router();
const checkAuth =  require('../middlewares/check-auth')


const OrdersController = require('../controllers/orders');

//Handle incoming GET requests to /ordes
router.get('/', checkAuth, OrdersController.orders_get_all);

//Handle incoming POST requests to /orders
router.post('/', OrdersController.orders_create_order);

//Handle incoming GET requests to /ordes/id
router.get('/:orderId', OrdersController.orders_get_order_by_id);

//Handle incoming Delete requests to /ordes
router.delete('/:orderId', OrdersController.orders_delete_order_by_id);

module.exports = router;