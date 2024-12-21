const mongoose = require('mongoose');

const serviceSchema = mongoose.Schema({
    _id: mongoose.Schema.Types.ObjectId,
    name: {type: String, require: true},
    description: {type: String, require: true},
    price: {type: Number, require: true},
    availability: {type: Boolean, require: true}
});

module.exports = mongoose.model('Service', serviceSchema);