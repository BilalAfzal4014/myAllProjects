const express = require("express");
const Sentry = require("@sentry/node");
const Tracing = require("@sentry/tracing");
const app = express();
Sentry.init({
    dsn: process.env.sentry_link,
    environment: process.env.sentry_environment,
    tracesSampleRate: 1.0,

});
const middlewaresManager = require('./express/middlewares/index');
const routesManager = require('./express/routes/index');
const cors = require('cors');
const RateLimit = require("express-rate-limit");

// const limiter = new RateLimit({
//
//     max: 100, // limit each IP to 100 requests per windowMs
//     delayMs: 0, // disable delaying - full speed until the max limit is reached
// });
//
// //  apply to all requests
// app.use(limiter);
app.use(express.json({limit: '50mb'}));
app.use(express.urlencoded({limit: '50mb'}));
middlewaresManager.applyMiddlewares(app);
middlewaresManager.setLayoutEngine(app);
middlewaresManager.setTemplatingEngine(app);
middlewaresManager.setPublicAssets(app);
routesManager.setupRoutes(app);
middlewaresManager.applyErrorMiddlewares(app);
module.exports = app;
