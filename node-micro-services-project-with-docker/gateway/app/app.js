const express = require("express");
const axios = require("axios");
const app = express();

app.use(express.urlencoded({extended: false}));
app.use(express.json());

let microServicesAddressMapper = {
    "user-service": "user-service-container",
    "course-service": "course-service-container",
};

app.use(function (req, res, next) {

    res.header("Access-control-allow-origin", "*");
    res.header("Access-control-allow-headers", "authorization");
    if (req.method.toLowerCase() === "options") {
        res.header("Access-control-allow-methods", "get, post put, delete");
        return res.status(200).json({});
    }
    next();
});

app.get('/favicon.ico', function (req, res) {
    //this is to the request handling of favicon.ico which browser sends by default
    return res.status(204)
});

app.use(function (req, res, next) {
    let fullUrl = req.protocol + '://' + req.get('host') + req.originalUrl;
    let urlPartition = req.originalUrl.split("/");

    let subUrl = ``;
    for (let i = 2; i < urlPartition.length; i++) {
        subUrl += `/${urlPartition[i]}`;
    }

    let url = `http://${microServicesAddressMapper[urlPartition[1]]}:3000${subUrl}`; //will hit the api of docker container present on same network
    //let url = `http://172.16.216.56:82${subUrl}`; //will hit api of server whose ip is mentioned
    //if above ip belongs to the machine where this(current) docker container is running then it will not hit that in success
    //let url = `http://localhost:82${subUrl}`; //will hit api of own world

    axios[req.method.toLowerCase()](url, req.body, {})
        .then((result) => {
            return res.status(result.data.status).json(result.data);
        }).catch((error) => {
        return res.status(500).json({
            status: 500,
            data: {},
            message: "Something Went Wrong"
        });
    });



});


module.exports = app;