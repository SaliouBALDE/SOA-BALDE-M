//Cross Origin Resource Sharing

//const whiteList = [
//    'https://www.google.be',
//    'http://localhost:3500', 
//    'https://www.geobios.com'
//];
//app.use(cors())

const corsOptions = (req, res, next) => {
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
}

module.exports = corsOptions;