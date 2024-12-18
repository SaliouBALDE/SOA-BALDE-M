const express = require('express');
const router = express.Router();
const mongoose = require('mongoose');

const Order = require('../models/order');
const Product = require('../models/product');
const { request } = require('../../app');

//Handle incoming GET requests to /ordes
router.get('/', (req, res, next) => {
    Order.find()
    .select('product quantity _id')
    .exec()
    .then(docs => {
        res.status(200).json({
            count: docs.length,
            orders: docs.map(doc => {
                return {
                    _id: doc._id,
                    product: doc.product,
                    quantity: doc.quantity,
                    request: {
                        type: 'GET',
                        url:`${req.protocol}://${req.get('host')}${req.originalUrl}/${doc._id}`
                    }
                }
            })
        });
    })
    .catch(err => {
        res.status(500).json({
            error: err
        });
    });
});
//Handle incoming POST requests to /orders
router.post('/', (req, res, next) => {
    //Make sure we can creat an order for a product we dont have
    Product.findById(req.body.productId)
    .then(product => {
        if (!product) {
            return res.status(404).json({
                message: 'Product not found in Database',
                error: err
            });
        }
        const order = new Order({
            _id: new mongoose.Types.ObjectId(),
            quantity: req.body.quantity,
            product: req.body.productId
        });
        return order.save();
    })
    .then(result => {
        console.log(result);
        res.status(201).json({
            message: 'Order created successfully!',
            createdOrder: {
                _id: result._id,
                product: result.product,
                quantity: result.quantity
            },
            request: {
                type: 'GET',
                url: `${req.protocol}://${req.get('host')}${req.originalUrl}/${result._id}`
            }
        });
    })
    .catch(err => {
        console.log(err);
        res.status(500).json({
            message: "Order could not be stored correctly...",
            error: err
        });
    });   
});

router.get('/:orderId', (req, res, next) => {
    Order.findById(req.params.orderId)
    .exec()
    .then(order => {
        if (!order){
            return res.status(404).json({
                message: "Order not found in Database",
                error: err
            });
        }
        res.status(200).json({
            order: order,
            request: {
                type: 'GET',
                url: `${req.protocol}://${req.get('host')}/orders`
            }

        });
    })
    .catch(err => {
        res.status(500).json({
            error: err
        })
    });
});

router.delete('/:orderId', (req, res, next) => {
    Order.deleteOne( {_id: req.params.orderId})
    .exec()
    .then(result => {
            res.status(200).json({
            message: 'Order succefully deleted ...',
            request: {
                type: 'POST',
                description: 'Link to creat a new order...',
                url: `${req.protocol}://${req.get('host')}/orders`,
                body: {
                    productId: 'ID',
                    quantity: 'Number'
                }
            }

        });
    })
    .catch(err => {
        res.status(500).json({
            error: err
        })
    });
});
module.exports = router;