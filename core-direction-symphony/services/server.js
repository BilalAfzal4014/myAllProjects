const http = require("http");
require("dotenv").config();
/**global means making variable global **/
global.connection = require("./app/config/config");
global.axios = require('axios').default;
global.jwt = require('jsonwebtoken');
global.auth = require('basic-auth');
global.randtoken = require('rand-token');
global.sgMail = require('@sendgrid/mail');
global.__basedir = __dirname;
const app = require("./app/app");
http.createServer(app).listen(3000);