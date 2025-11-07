const express = require('express');
const app = express();
const middlewaresManager = require('./express/middlewares');
const routesManager = require('./express/routes');

middlewaresManager.applyMiddlewares(app);
routesManager.setupRoutes(app);
middlewaresManager.applyErrorMiddlewares(app);

module.exports = app;