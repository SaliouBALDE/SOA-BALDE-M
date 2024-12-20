const { logEvents } = require('./logEvents');

const errorHandler = (err, req, res, next) => {
    logEvents(`${err.name}: ${err.message}`, 'errLog.txt');
    res.status(error.status || 500);
    res.json({
        error: {
            message: error.message
        }
    });
}

module.exports = errorHandler;