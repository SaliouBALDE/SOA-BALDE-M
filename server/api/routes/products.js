const express = require('express');
const router = express.Router();
const checkAuth = require('../middlewares/check-auth');
const ProductsController = require('../controllers/products');
const  { authorize } = require('../middlewares/auth');
const ROLES = require("../config/roles_list");

//Handle to get all products
router.get('/', ProductsController.products_get_all);

//Hangle to creat a product
router.post('/', checkAuth, ProductsController.products_create_product);

//Handle to GET product by Id
router.get('/:productId', ProductsController.products_get_porduct_by_id);

//Handle update product
router.patch('/:productId', 
    ProductsController.products_update_product_by_id);

//Handle delete product by Id
router.delete('/:productId', 
    ProductsController.products_delete_products_by_id);

module.exports = router;