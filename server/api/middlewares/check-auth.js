const AuthJWT = require('../helpers/jwt')

module.exports = (req, res, next) => {
    try {
        const token = req.headers.authorization.split(" ")[1];
        console.log(token);
        const decoded = AuthJWT.verifyToken(token, process.env.JWT_KEY);

        req.userData = decoded;
        next();
    } catch (error) {
       return res.status(401).json({
        message: "Invalid or expired token ..."
       });
    }
};