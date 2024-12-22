const mongoose = require('mongoose');

const Order = require('../models/order');
const Product = require('../models/product');
const Service = require('../models/service');
const User = require('../models/user');

exports.orders_get_all = async (req, res, next) => {
    try {
        const orders = await Order.find()
        .populate('user', 'email')
        .populate('items.item')
        res.status(200).json({
            orders 
        });
        
    } catch (error) {
        res.status(500).json({
            error: err.message
        });
    }
}

exports.orders_create_order = async (req, res, next) => {

    try {
        const {user, items} = req.body;

       
        const foundUser = await User.findById(user);
        if (!foundUser) {  //User exist ?
            return res.status(404).json({ error: 'User not Found (404)' })
        }
    
        let totalAmount = 0;
        for (const orderItem of items) {
            const { type, item, quantity } = orderItem;
    
            if (type === 'Product') {//Product exist ?
                const product = await Product.findById(item);
                if (!product) {
                    return res.status(404).json({
                        error: `Product with Id ${item} not Found!`
                    });
                }
                if (product.stock < quantity) {
                    return res.status(400).json({ 
                        error: `Not enough stock for product ${product.name}` 
                    });
                }
                totalAmount += product.price * quantity;
        
                // Reduce stock for the product
                product.stock -= quantity;
                await product.save();
            } else if (type === 'Service') { //Service exist ?
                const service = await Service.findById(item);
                if (!service) {
                    return res.status(404).json({ 
                        error: `Service with ID ${item} not found` 
                    });
                }
                totalAmount += service.rate * quantity;
            } else {
                return res.status(400).json({ 
                    error: `Invalid item type: ${type}` 
                });
            }
        }
    
        // Create the order
        const newOrder = new Order({ user, items, totalAmount });
        const savedOrder = await newOrder.save();
    
        res.status(201).json(savedOrder);
    } catch (err) {
        res.status(500).json({
            error: err.message
        });
    }
}

exports.orders_get_order_by_id = async (req, res, next) => {
    try {
        const id = req.params.orderId;
        console.log("id:", id);
        const order = await Order.findById(id)
          .populate('user', 'username email')
          .populate('items.item');
        if (!order) {
            return res.status(404).json({ 
                error: 'Order not found' 
            });
        }
    
        res.json(order);

      } catch (err) {
        res.status(500).json({ 
            error: err.message 
        });
      }
}

exports.orders_delete_order_by_id = async (req, res, next) => {
    try {
        const id = req.params.orderId;
        const deletedOrder = await Order.findByIdAndDelete(id);
        if (!deletedOrder) {
            return res.status(404).json({ 
                error: 'Order not found' 
            });
        }
    
        res.json({ message: 'Order deleted successfully' });

      } catch (err) {
        res.status(500).json({ 
            error: err.message 
        });
      }
}

//modifer comme product update
exports.orders_update_order_by_id = async (req, res) => {
    try {
      const { id } = req.params;
      const { items } = req.body;
  
      const order = await Order.findById(id);
      if (!order) {
            return res.status(404).json({ 
                error: 'Order not found' 
            });
        }
  
      // Update order items and recalculate total amount
      let totalAmount = 0;
  
      for (const orderItem of items) {
        const { type, item, quantity } = orderItem;
  
        if (type === 'Product') {
          const product = await Product.findById(item);
          if (!product) {
                return res.status(404).json({ 
                    error: `Product with ID ${item} not found` 
                });
            }
  
          totalAmount += product.price * quantity;

        } else if (type === 'Service') {
          const service = await Service.findById(item);
          if (!service) {
                return res.status(404).json({ 
                    error: `Service with ID ${item} not found` 
                });
            }
  
          totalAmount += service.rate * quantity;
        } else {
          return res.status(400).json({ 
                error: `Invalid item type: ${type}` 
            });
        }
      }
  
      order.items = items;
      order.totalAmount = totalAmount;
  
      const updatedOrder = await order.save();
      res.json(updatedOrder);
    } catch (err) {
      res.status(500).json({
            error: err.message 
        });
    }
  };