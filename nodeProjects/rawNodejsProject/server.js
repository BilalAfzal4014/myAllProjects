var http = require('http');
var app = require('./app');


http.createServer(app.requestHandler).listen(8000);