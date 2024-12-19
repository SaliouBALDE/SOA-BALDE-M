const express =  require('express');
const app =  express();
const morgan = require('morgan');
const bodyParser =  require('body-parser');
const mongoose =  require('mongoose');

const productRoutes = require('./api/routes/products');
const orderRoutes = require('./api/routes/orders');
const userRoutes = require('./api/routes/users');

mongoose.connect(
    `mongodb+srv://` + 
    process.env.MONGO_ATLAS_DATABASE_USERNAME + `:` + 
    process.env.MONGO_ATLAS_DATABASE_PASSWORD + 
    `@cluster0.22kp4.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0`
);
mongoose.Promise = global.Promise;

app.use(morgan('dev'));
app.use(bodyParser.urlencoded({extended: false}));//To extrate url
app.use(bodyParser.json());//To extrate json date

app.use((req, res, next) => {
    res.header("Access-Control-Allow-Origin", "*");//We give access to any client
    res.header(
        "Access-Control-Allow-Headers", 
        "Origin, X-Requested-With, Content-Type, Accept, Authorization"
    );
    if(req.method ==='OPTIONS') {
        res.header('Access-Control-Allow-Methods', 'PUT, POST, PATCH, DELETE, GET');
        return res.status(200).json({});
    }
    next();
});

//Routes which should handle requests (midelwares)
app.use('/products', productRoutes);
app.use('/orders', orderRoutes);
app.use('/users', userRoutes);

app.use((req, res, next) => {
    const error = new Error('Not found');
    error.status = 404;
    next(error);
})

app.use((error, req, res, next) => {
    res.status(error.status || 500);
    res.json({
        error: {
            message: error.message
        }
    });
});

module.exports = app;