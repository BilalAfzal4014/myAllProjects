const path = require("path");
const express = require("express");
const app = express();
const router = express.Router();
const checkoutRoutes = require("./routes/checkout-routes");

app.use(express.urlencoded({ extended: false }));
app.use(express.json());

app.set('views engine', 'ejs');
app.set('views', path.join(__dirname, '/views'))

app.use(function(req, res, next){
    res.header("Access-control-allow-origin", "*");
    res.header("Access-control-allow-headers", "authorization, content-type");

    if (req.method === "OPTIONS") {
        res.header("Access-control-allow-methods", "GET, PUT, POST, PATCH, DELETE");
        res.status(200).json({});
        return;
    }

    next();
});

router.use("/checkout", checkoutRoutes);
app.use("/v1", router);

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

