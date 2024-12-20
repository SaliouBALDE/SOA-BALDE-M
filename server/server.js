const http = require('http');
const app = require('./app');
const mongoose =  require('mongoose');
const connectDB = require('./api/config/dbConnect');
const { constructFromSymbol } = require('date-fns/constants');

const PORT = process.env.PORT || 3500;

const server = http.createServer(app);

// Connect to mongoDB
connectDB();

mongoose.connection.once('open', () => {
    console.log('Connected to MongoDB');
    server.listen(PORT, () => {
        console.log(`Node.js App running on port ${PORT} ...`)
    });
})

