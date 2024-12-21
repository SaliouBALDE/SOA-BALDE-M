const mongoose = require('mongoose');
const Service = require('../models/service');
const service = require('../models/service');

exports.services_get_all = (req, res, next) => {
    Service.find()
        .select('name price description availability _id')
        .exec()
        .then(docs => {
            const response = {
                count: docs.length,
                services: docs.map(doc => {
                    return {
                        name: doc.name,
                        price: doc.price,
                        description: doc.description,
                        availability: doc.availability,
                        _id: doc._id,
                        request: {
                            type: 'GET',
                            url: `${req.protocol}://${req.get('host')}${req.originalUrl}/${doc._id}`
                        }
                    }
                })
            };
 
            res.status(200).json(response);
        })
        .catch(err => {
            console.log(err);
            res.status(500).json({
                error: err
            });
        });
}

exports.services_create_service = (req, res, next) => {
    
    const service = new Service({
        _id: new mongoose.Types.ObjectId(),
        name: req.body.name,
        description: req.body.description,
        price: req.body.price,
        availability: req.body.availability
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
                    price: result.price,
                    availability: result.availability,
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

exports.services_get_services_by_id = (req, res, next) => {
    const id = req.params.serviceId;
    Service.findById(id)
        .select('name price description availability _id')
        .exec()
        .then(doc => {
            console.log("From database", doc);
            if(doc) {
                res.status(200).json({
                    service: doc,
                    request: {
                        type: 'GET',
                        url: `${req.protocol}://${req.get('host')}/services`
                    }
                });
            } else {
                res.status(404).json({
                    message: 'No valid entry found for provides Id'
                });
            }
        })
        .catch(err => {
            console.log(err);
            res.status(500).json({error: err});
        });
}

exports.services_delete_service_by_id = (req, res, next) => {
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
                    price: 'Number', 
                    descriptio: 'String', 
                    availability: 'Boolean' 
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

exports.services_update_service_by_id = (req, res, next) => {
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