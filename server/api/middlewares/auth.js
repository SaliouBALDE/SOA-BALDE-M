const { verifyToken } = require("../helpers/jwt");

const authorize = (requiredRoles) => {
  return (req, res, next) => {
    const token = req.headers.authorization?.split(" ")[1];
    if (!token) return res.status(401).json({ 
      error: "Auth: Token required" 
    });

    try {
      const user = verifyToken(token, process.env.JWT_KEY);
      console.log("User extract token", user);
      
      console.log(user.roles);
      const userRoles = Object.values(user.roles || {});
      console.log(userRoles);

      const hasAccess = requiredRoles.some((role) => userRoles.includes(role));

      if (!hasAccess) return res.status(403).json({ 
        error: "Auth: Access denied" 
      });

      req.user = user; // Ajouter l'utilisateur à la requête
      next();
    } catch (error) {
      res.status(401).json({ 
        error: "Auth: Invalid or expired token" 
      });
    }
  };
};

  module.exports = { authorize };
