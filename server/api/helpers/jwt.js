const crypto = require("crypto");

// Créer le header
const createHeader = () => {
  const header = {
    alg: "HS256",
    typ: "JWT"
  };
  return Buffer.from(JSON.stringify(header)).toString("base64url");
};

// Créer le payload avec une durée de validité
const createPayload = (data, expiresIn) => {
  const payload = {
    ...data,
    exp: Math.floor(Date.now() / 1000) + expiresIn // Durée en secondes
  };
  return Buffer.from(JSON.stringify(payload)).toString("base64url");
};

// Créer la signature
const createSignature = (header, payload, secret) => {
  const data = `${header}.${payload}`;
  return crypto
    .createHmac("sha256", secret)
    .update(data)
    .digest("base64url");
};

// Générer un token
exports.createToken = (data, secret, expiresIn = 3600) => {
  const header = createHeader();
  const payload = createPayload(data, expiresIn);
  const signature = createSignature(header, payload, secret);
  return `${header}.${payload}.${signature}`;
};

// Vérifier un token
exports.verifyToken = (token, secret) => {
  const [headerB64, payloadB64, signature] = token.split(".");
  const newSignature = createSignature(headerB64, payloadB64, secret);
  
  if (newSignature !== signature) {
    throw new Error("Invalid token");
  }

  const payload = JSON.parse(Buffer.from(payloadB64, "base64url").toString());

  // Vérifier si le token a expiré
  if (payload.exp && payload.exp < Math.floor(Date.now() / 1000)) {
    throw new Error("Token has expired");
  }

  return payload;
};