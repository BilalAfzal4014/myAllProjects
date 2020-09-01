const nodemailer = require('nodemailer');

const transporter = nodemailer.createTransport({
    service: 'gmail',
    auth: {
        user: 'bilalafzal4014@gmail.com',
        pass: 'c.ompatiable1'
    }
});

//one more thing turn on 'less secure app' btn of your gmail account

module.exports = transporter;