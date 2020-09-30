const express = require("express");
const app = express();
const userRoutes = require("./routes/user-routes");

app.use(express.urlencoded({extended: false}));
app.use(express.json());

app.use(function (req, res, next) {

    res.header("Access-control-allow-origin", "*");
    res.header("Access-control-allow-headers", "authorization");
    if (req.method.toLowerCase() === "options") {
        res.header("Access-control-allow-methods", "get, post put, delete");
        return res.status(200).json({});
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