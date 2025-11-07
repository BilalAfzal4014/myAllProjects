const nodemailer = require('nodemailer');

const transporter = nodemailer.createTransport({
    service: process.env.service,
    auth: {
        user: process.env.email,
        pass: process.env.password
    }
});

module.exports = transporter;