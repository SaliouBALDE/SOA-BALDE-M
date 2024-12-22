const mongoose = require('mongoose');
const Product = require('../models/product');


exports.products_get_all = (req, res, next) => {
    Product.find()
        .select('name price description stock _id')
        .exec()
        .then(docs => {
            const response = {
                count: docs.length,
                products: docs.map(doc => {
                    return {
                        name: doc.name,
                        price: doc.price,
                        description: doc.description,
                        stock: doc.stock,
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

exports.products_create_product = (req, res, next) => {
    
    const product = new Product({
        _id: new mongoose.Types.ObjectId(),
        name: req.body.name,
        price: req.body.price,
        description: req.body.description,
        stock: req.body.stock
    });
    //To save the created product in the database
    product
        .save()
        .then(result => {
            console.log(result);
            res.status(201).json({
                message: 'Created product succesfully',
                createdProduct: {
                    name: result.name,
                    price: result.price,
                    description: result.description,
                    stock: result.stock,
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

exports.products_get_porduct_by_id = (req, res, next) => {
    const id = req.params.productId;
    Product.findById(id)
        .select('name price description stock _id')
        .exec()
        .then(doc => {
            console.log("From database", doc);
            if(doc) {
                res.status(200).json({
                    product: doc,
                    request: {
                        type: 'GET',
                        url: `${req.protocol}://${req.get('host')}/products`
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

exports.products_update_product_by_id = (req, res, next) => {
    const id = req.params.productId;
    const updateOps = {};
    for (const ops of req.body) {  //For the update we change one attribute at time
        updateOps[ops.propName] = ops.value;
    }
    Product.updateOne({ _id: id }, {$set: updateOps })
    .exec()
    .then(result => {
        res.status(200).json({
            message: 'Prodct Updated',
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

exports.products_delete_products_by_id = (req, res, next) => {
    const id = req.params.productId;
    Product.deleteOne({ _id: id })
    .exec()
    .then(result => {
        res.status(200).json({
            message: 'Product deleted',
            request: {
                type: 'POST',
                url: `${req.protocol}://${req.get('host')}/products`,
                body: {
                    name: 'String', 
                    price: 'Number',
                    description: 'String',
                    stock: 'Number'
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