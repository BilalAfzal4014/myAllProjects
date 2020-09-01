const http = require("http");
const socket = require('socket.io');

const server = http.createServer(function (req, res) {
    res.write("Hello");
    res.end();
});

const io = socket(server /*,{
    handlePreflightRequest: (req, res) => {
        const headers = {
            "Access-Control-Allow-Headers": "Content-Type, Authorization",
            "Access-Control-Allow-Origin": "*", //or the specific origin you want to give access to,
            "Access-Control-Allow-Credentials": true
        };
        res.writeHead(200, headers);
        res.end();
    }
}*/);
//io.set('origins', '*:*');

//io.set('origins', 'http://5baa1cac.ngrok.io');
//io.set('origins', 'http://a4920ca7.ngrok.io');

/*
io.origins((origin, callback) => {
    console.log("origin", origin);
    if (origin !== 'http://a4920ca7.ngrok.io') {
        return callback('origin not allowed', false);
    }
    console.log("i m here");
    callback(null, true);
});*/

const socketLogic = require("./socket/index.js")(io);

server.listen(3000);