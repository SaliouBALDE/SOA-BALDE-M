const http = require('http');
const app = require('./app');

const PORT = process.env.PORT || 3500;

const server = http.createServer(app);

server.listen(PORT, () => {
    console.log(`Node.js App running on port ${PORT} ...`)
});