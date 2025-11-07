const sendGrid = require('sendgrid')(process.env.send_grid_api_key);
module.exports = sendGrid;