const mongoose = require('mongoose');

const connectDB =  async () => {
    try {
        await mongoose.connect(process.env.MONGODB_COMPASS_URI, {
            useUnifiedTopology: true,
            useNewUrlParser: true
        });
    } catch (err) {
        console.error(err);
    }
}

module.exports = connectDB;