const http = require("http");
const app = require("./app/app");
require('dotenv').config();
global.masterDB = require("./app/dbs-config/db-master-config");
global.redisCache = require("./app/redis-config/redis-config");

http.createServer(app).listen(3000);