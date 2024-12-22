const express = require('express');
const router = express.Router();
const checkAuth = require('../middlewares/check-auth');
const ServicesController = require('../controllers/services');
const  { authorize } = require('../middlewares/auth');
const ROLES = require("../config/roles_list");

//Handle to get all services
router.get('/', ServicesController.services_get_all);

//Hangle to create a service
router.post('/', ServicesController.services_create_service);

//Handle to GET product by Id
router.get('/:serviceId', ServicesController.services_get_services_by_id);

//Handle update product
router.patch('/:serviceId', 
ServicesController.services_update_service_by_id);

//Handle delete product by Id
router.delete('/:serviceId',
ServicesController.services_delete_service_by_id);

module.exports = router;