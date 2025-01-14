const mongoose = require('mongoose');

const connectDB =  async () => {
    try {
        await mongoose.connect("mongodb://localhost:27017/GeobiosDB", {
            useUnifiedTopology: true,
            useNewUrlParser: true
        });
    } catch (err) {
        console.error(err);
    }
}

module.exports = connectDB;