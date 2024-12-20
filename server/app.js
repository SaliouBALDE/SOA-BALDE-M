const express =  require('express');
const app =  express();

const morgan = require('morgan');
const bodyParser =  require('body-parser');
const mongoose =  require('mongoose');
const path = require('path');
const { logger } = require('./api/logEvents');
const errorHandler= require('./api/errorHandler');

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

//Custom middleware logger
app.use(logger);

//CORS(Cross Origin Resource Sharing) error handler
//const whiteList = ['https://www.google.be','http://localhost:3500', 'https://www.geobios.com'];
//app.use(cors())

app.use((req, res, next) => {
    console.log(req.url, req.method);
    res.header("Access-Control-Allow-Origin", 'http://localhost:3500');// '*' to give access to any client
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