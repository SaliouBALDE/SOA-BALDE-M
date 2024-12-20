const http = require('http');
const app = require('./app');
const logEvents = require('./logEvents');

const EventEmitter = require('events');

class MyEmitter extends EventEmitter {};

//initialize object
const myEmitter = new MyEmitter();

//Add lisner for log event
myEmitter.on('log', (msg) => logEvents(msg));

setTimeout(() => {
    //Emit event
    myEmitter.emit('log', 'Log event emitted! ');
}, 2000);

const PORT = process.env.PORT || 3500;

const server = http.createServer(app);


server.listen(PORT, () => {
    console.log(`Node.js App running on port ${PORT} ...`)
});