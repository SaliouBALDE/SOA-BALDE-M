const express = require('express');
const router = express.Router();
const mongoose = require('mongoose');

const Product = require('../models/product');

//Handle to get all products
router.get('/', (req, res, next) => {
    Product.find()
        .exec()
        .then(docs => {
            console.log(docs);
            //if (docs.lenght >= 0) {
                res.status(200).json(docs);
            //} else {
            //    res.status(404).json({
            //        message: 'No rentries found'
            //    });
            //}
        })
        .catch(err => {
            console.log(err);
            express.status(500).json({
                error: err
            });
        });
});
//Hangle to creat a product
router.post('/', (req, res, next) => {
    
    const product = new Product({
        _id: new mongoose.Types.ObjectId(),
        name: req.body.name,
        price: req.body.price
    });
    //To save the created product in the database
    product
        .save()
        .then(result => {
            console.log(result);
            res.status(201).json({
                message: 'Handling POST requests to /products',
                createdProduct: result
            });
        })
        .catch(err => {
            console.log(err);
            res.status(500).json({
                error: err
            })
        });
});

//Handle to GET product by Id
router.get('/:productId', (req, res, next) => {
    const id = req.params.productId;
    Product.findById(id)
        .exec()
        .then(doc => {
            console.log("From database", doc);
            if(doc) {
                res.status(200).json(doc);
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
});

router.patch('/:productId', (req, res, next) => {
    const id = req.params.productId;
    const updateOps = {};
    for (const ops of req.body) {  //For the update we mean change juste the name or the price, not both
        updateOps[ops.propName] = ops.value;
    }
    Product.updateOne({ _id: id }, {$set: updateOps })
    .exec()
    .then(result => {
        console.log(result);
        res.status(200).json(result);
    })
    .catch( err => {
        console.log(err);
        res.status(500).json({
            error: err
        });
    });
});

//Handle delete product by Id
router.delete('/:productId', (req, res, next) => {
    const id = req.params.productId;
    Product.deleteOne({ _id: id })
    .exec()
    .then(result => {
        res.status(200).json(result);
    })
    .catch(err => {
        console.log(err);
        res.status(500).json({
            error: err
        });
    });
});

module.exports = router;