const http = require('http');
const app = require('./app');
const mongoose =  require('mongoose');
const connectDB = require('./api/config/dbConnect');
const { constructFromSymbol } = require('date-fns/constants');

const PORT = process.env.PORT || 3500;

const server = http.createServer(app);

// Connect to mongoDB
connectDB();

//Connection to mongodb Atlas
mongoose.connection.once('open', () => {
    console.log('Connected to MongoDB Atlas');
    server.listen(PORT, () => {
        console.log(`Node.js App running on port ${PORT} ...`)
    });
})
/*
//Connection to mongodb Compass
mongoose.connect('mongodb://0.0.0.0:27017/test');
mongoose.connection.once('open', () => {
    console.log('Connected to MongoDB Compass');
    server.listen(PORT, () => {
        console.log(`Node.js App running on port ${PORT} ...`)
    });
})

conn.on('error', () => {
    console.log('Connected to MongoDB Compass failled!');
    process.exit();
})*/

