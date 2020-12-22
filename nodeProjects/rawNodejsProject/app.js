var fs = require('fs');
var url = require('url');


function renderHtml(path, response){
	fs.readFile(path , null, function(error, data){
			if(error){
				response.writeHead(404);
				response.write("File not Found");
			}
			else{
				response.write(data);
			}
			response.end();
	});
}


module.exports = {  
	requestHandler: function(request, response){
		response.writeHead(200, {'content-type': 'text/html'});	
		var path = url.parse(request.url).pathname;
		switch(path){
			case '/':
				renderHtml('index.html', response);
				break;
			case '/login':
				renderHtml('login.html', response);
				break;
			default:
				response.writeHead(404);
				response.write("Route not Found");
				response.end();
		}
	},
};