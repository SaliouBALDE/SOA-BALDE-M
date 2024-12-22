const mongoose = require('mongoose');
const Service = require('../models/service');
const User = require('../models/user');

exports.services_get_all = async (req, res) => {
    try {
      const services = await Service.find().populate('employees', 'username email');
      res.status(200).json(services);
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
};
  

exports.services_create_service = (req, res) => {
    
    const service = new Service({
        _id: new mongoose.Types.ObjectId(),
        name: req.body.name,
        description: req.body.description,
        rate: req.body.rate,
        employee: req.body.employee
    });
    //To save the created product in the database
    service
        .save()
        .then(result => {
            console.log(result);
            res.status(201).json({
                message: 'Created service succesfully',
                createdService: {
                    name: result.name,
                    description: result.description,
                    createdAt: result.createdAt,
                    rate: result.rate,
                    employee: result.employee,
                    _id: result._id,
                    request: {
                        type: 'GET',
                        url: `${req.protocol}://${req.get('host')}${req.originalUrl}/${result._id}`
                    }
                }
            });
        })
        .catch(err => {
            console.log(err);
            res.status(500).json({
                error: err
            })
        });
}

exports.services_get_services_by_id = async (req, res) => {
    try {
      const { serviceId } = req.params;
      const service = await Service.findById(serviceId).populate('employees', 'username email');
      if (!service) {
        return res.status(404).json({ error: 'Service not found' });
      }
      res.status(200).json(service);
    } catch (err) {
      res.status(500).json({ error: err.message });
    }
  };

exports.services_delete_service_by_id = (req, res) => {
    const id = req.params.serviceId;
    Service.deleteOne({ _id: id })
    .exec()
    .then(result => {
        res.status(200).json({
            message: 'Service deleted',
            request: {
                type: 'POST',
                url: `${req.protocol}://${req.get('host')}/services`,
                body: {
                    name: 'String',
                    descriptio: 'String', 
                    rate: 'Number' 
                }
            }
        });
    })
    .catch(err => {
        console.log(err);
        res.status(500).json({
            error: err
        });
    });
}

exports.services_update_service_by_id = (req, res) => {
    const id = req.params.serviceId;
    const updateOps = {};
    for (const ops of req.body) {  //For the update we mean change juste one attribute at time
        updateOps[ops.propName] = ops.value;
    }
    Service.updateOne({ _id: id }, {$set: updateOps })
    .exec()
    .then(result => {
        res.status(200).json({
            message: 'Service Updated',
            request: {
                type: 'GET',
                url: `${req.protocol}://${req.get('host')}${req.originalUrl}`
            }
        });
    })
    .catch( err => {
        console.log(err);
        res.status(500).json({
            error: err
        });
    });
}

exports.servicess_assign_employees_to_service = async (req, res) => {

    try {
      const { serviceId } = req.params;
      const { employeeIds } = req.body;
  
      // Validate employees
      const employees = await User.find({ _id: { $in: employeeIds } });

      if (employees.length !== employeeIds.length) {
        return res.status(400).json({ 
            error: 'One or more employees not found' 
        });
      }

      // Update the service with the list of employees
      const updatedService = await Service.findByIdAndUpdate(
        serviceId,
        { $addToSet: { employees: { $each: employeeIds } } }, // Avoid duplicates
        { new: true }
      ).populate('employees', 'username email');
  
      if (!updatedService) {
        return res.status(404).json({ 
            error: 'Service not found' 
        });
      }
  
      res.status(200).json(updatedService);

    } catch (err) {
        res.status(500).json({ 
            //error: err.message 
            error: 'Assign: user not found'
        });
    }
};