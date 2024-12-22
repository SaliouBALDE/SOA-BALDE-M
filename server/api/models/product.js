const mongoose = require('mongoose');

const productSchema = new mongoose.Schema({
    _id: mongoose.Schema.Types.ObjectId,
    name: {type: String, require: true},
    description: {type: String},
    price: {type: Number, require: true},
    stock: {type: Number, default: 0}
});

module.exports = mongoose.model('Product', productSchema);