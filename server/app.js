const express =  require('express');
const morgan = require('morgan');
const bodyParser =  require('body-parser');

const { logger } = require('./api/logEvents');
const errorHandler= require('./api/errorHandler');
const corsOptions =require('./api/config/corsOptions')
const productRoutes = require('./api/routes/products');
const serviceRoutes = require('./api/routes/services');
const orderRoutes = require('./api/routes/orders');
const userRoutes = require('./api/routes/users');

const app =  express();
app.use(morgan('dev'));
app.use(bodyParser.urlencoded({extended: false}));//To extrate url
app.use(bodyParser.json());//To extrate json date

//Custom middleware logger
app.use(logger);

//CORS error handler
app.use(corsOptions);

//Routes which should handle requests (midelwares)
app.use('/products', productRoutes);
app.use('/services', serviceRoutes);
app.use('/orders', orderRoutes);
app.use('/users', userRoutes);

app.all('*', (req, res, next) => {
    res.status(404);
    if (req.accepts('json')) {
        res.json({ error: "404 Not Found"})
    } else {
        res.type('txt').send('404 Not Found')
    }
    next(error);
})

app.use(errorHandler);

module.exports = app;