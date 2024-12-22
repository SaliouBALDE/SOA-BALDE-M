const express = require('express');
const router = express.Router();
const checkAuth = require('../middlewares/check-auth');
const ServicesController = require('../controllers/services');
const  { authorize } = require('../middlewares/auth');
const ROLES = require("../config/roles_list");

//Handle to get all services
router.get('/', checkAuth, ServicesController.services_get_all);

//Handle to create a service
router.post('/', checkAuth, 
    ServicesController.services_create_service);

// Assign employee to a service
router.post('/:serviceId/employees',checkAuth, authorize([ROLES.Employee]),
    ServicesController.servicess_assign_employees_to_service);

//Handle to GET product by Id
router.get('/:serviceId', checkAuth,
     ServicesController.services_get_services_by_id);

//Handle update product
router.patch('/:serviceId', checkAuth, authorize([ROLES.Admin, ROLES.Employee]),
ServicesController.services_update_service_by_id);

//Handle delete product by Id
router.delete('/:serviceId', checkAuth, authorize([ROLES.Admin, ROLES.Employee]),
ServicesController.services_delete_service_by_id);



module.exports = router;