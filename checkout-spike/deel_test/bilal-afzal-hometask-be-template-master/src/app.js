const express = require('express');
const bodyParser = require('body-parser');
const {sequelize} = require('./database/models')
const {
    contractRoutes,
    jobRoutes,
    balanceRoutes,
    adminRoutes
} = require('../src/routes');
const {getProfile} = require("./middleware/getProfile");
const app = express();
app.use(bodyParser.json());
app.set('sequelize', sequelize)
app.set('models', sequelize.models)


app.use('/contracts', getProfile, contractRoutes);
app.use('/jobs', getProfile, jobRoutes);
app.use('/balances', balanceRoutes);
app.use('/admin', adminRoutes);

module.exports = app;
