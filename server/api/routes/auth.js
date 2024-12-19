const express = require("express");
const { createToken, verifyToken } = require("../utils/jwt");

const router = express.Router();

// Clé secrète (à garder sécurisée)
const SECRET_KEY = "votre_clé_secrète";

// Mock utilisateur
const mockUser = { id: 1, email: "user@example.com", password: "password123" };

// Route pour se connecter et obtenir un token
router.post("/login", (req, res) => {
  const { email, password } = req.body;

  // Vérification utilisateur (exemple simplifié)
  if (email === mockUser.email && password === mockUser.password) {
    const token = createToken({ id: mockUser.id, email: mockUser.email }, SECRET_KEY);
    return res.json({ token });
  }

  res.status(401).json({ error: "Invalid email or password" });
});

// Route pour vérifier un token
router.get("/protected", (req, res) => {
  const token = req.headers.authorization?.split(" ")[1]; // Bearer TOKEN

  if (!token) {
    return res.status(401).json({ error: "Token required" });
  }

  try {
    const userData = verifyToken(token, SECRET_KEY);
    res.json({ message: "Access granted", user: userData });
  } catch (error) {
    res.status(401).json({ error: "Invalid or expired token" });
  }
});

module.exports = router;
