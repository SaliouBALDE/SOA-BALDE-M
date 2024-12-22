const mongoose = require('mongoose');

const serviceSchema = new mongoose.Schema({
    _id: mongoose.Schema.Types.ObjectId,
    name: {type: String, require: true},
    description: {type: String},
    rate: {type: Number, require: true},
    createdAt: {type: Date, default: Date.now}
});

module.exports = mongoose.model('Service', serviceSchema);