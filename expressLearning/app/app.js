const path = require("path");
const express = require("express");
const app = express();
const userRoutes = require("./routes/userRoutes");

app.use(express.urlencoded({ extended: false }));
app.use(express.json());

//console.log(__dirname); //current directory
// var/www/html/checkout-spike/app

//console.log(path.join(__dirname, '../views'));
//one step back into current directory and then into views folder(if any view folder)
// /var/www/html/checkout-spike/views

app.use(function (req, res, next) {
    console.log("This will console when url hits");
    res.header("Access-control-allow-origin", "*");
    res.header("Access-control-allow-headers", "authorization, content-type");

    if (req.method === "OPTIONS") {
        res.header("Access-control-allow-methods", "GET, PUT, POST, PATCH, DELETE");
        res.status(200).json({});
        return;
    }

    next();
});

app.use("/", userRoutes);

app.use(function (req, res, next) {
    let error = new Error("Not Found");
    error.status = 400;
    next(error);
});

app.use(function (error, req, res, next) {

    res.status(error.status || 500).json({
        error: {
            message: error.message
        }
    });
});

module.exports = app;