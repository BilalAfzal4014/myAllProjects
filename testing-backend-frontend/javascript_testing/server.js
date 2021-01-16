const http = require("http");
const app = require("./app/app");

global.server = "server";

http.createServer(app).listen(3000);

/*function(req, res, next){
    res.setHeader('Content-Type', 'application/json');
    res.write(JSON.stringify({
        status: 200,
        data: {
            users: [{
                name: "Bilal",
                gender: "Male"
            },{
                name: "Shahzaib",
                gender: "Male"
            }]
        },
        message: ["get user details"]
    }));
    res.end();
}*/