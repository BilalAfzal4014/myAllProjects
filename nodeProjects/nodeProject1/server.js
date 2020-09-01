var http = require('http');
var module1 = require('./module1');
var fs = require('fs');


function onRequest(request, response){
	//response.writeHead(200, {'content-type': 'text/plain'}); // it will load html as text
	response.writeHead(200, {'content-type': 'text/html'});		// it will render html
	fs.readFile('./index.html', null, function(error, data){ // data is basically html file
			if(error){
				response.writeHead(404);
				response.write("File not Found");
			}
			else{
				response.write(data);
			}
			response.end();
	});


	//response.write('<h1>node js server running</h1>');
	//response.write(module1.string);
	//module1.myFunction();

	//response.end();
}

http.createServer(onRequest).listen(8001);
