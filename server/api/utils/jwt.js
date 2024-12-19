const crypto = require("crypto");

// Créer le header
const createHeader = () => {
  const header = {
    alg: "HS256",
    typ: "JWT"
  };
  return Buffer.from(JSON.stringify(header)).toString("base64url");
};

// Créer le payload
const createPayload = (data) => {
  return Buffer.from(JSON.stringify(data)).toString("base64url");
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
const createToken = (data, secret) => {
  const header = createHeader();
  const payload = createPayload(data);
  const signature = createSignature(header, payload, secret);
  return `${header}.${payload}.${signature}`;
};

// Vérifier un token
const verifyToken = (token, secret) => {
  const [headerB64, payloadB64, signature] = token.split(".");
  const newSignature = createSignature(headerB64, payloadB64, secret);
  if (newSignature !== signature) {
    throw new Error("Invalid token");
  }
  return JSON.parse(Buffer.from(payloadB64, "base64url").toString());
};

module.exports = { createToken, verifyToken };
