const mongoose = require('mongoose');

const orderSchema = new mongoose.Schema({
    user: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'User',
        require: true
    },
    items: [
        {
            type: { type: String, enum: ['Product', 'Service'], require:  true },
            item: {
                type: mongoose.Schema.Types.ObjectId,
                refPath: 'item.type',
                require: true
            },
            quantity: {type: Number, default: 1}
        },
    ],
    totalAmount: { type: Number, require: true},
    createdAt: { type: Date, default: Date.now},
    deliveryDate: { type: Date },
});

module.exports = mongoose.model('Order', orderSchema);