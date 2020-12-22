const http = require("http");

http.createServer(function(req, res, next){
	res.write("Hello I am a node server. Am i changed");
	res.end();
}).listen(3000);
