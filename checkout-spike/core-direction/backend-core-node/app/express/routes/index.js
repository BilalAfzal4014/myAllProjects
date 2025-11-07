const router = require("express").Router();
const baseRoutes = require("./base-routes");
const loginRoutes = require("./login-routes");
const forgotPasswordRoutes = require("./forgot-password-routes");
const signUpRoute = require("./signup-routes");
const refreshTokenRoute = require("./refresh-token-routes");
const filterRoute = require("./filter-routes");
const userRoute = require("./user-routes");
const whiteLabelRoute = require("./white-label-routes");
const companyRoute = require("./company-routes");
const activityRoute = require("./activity-routes");
const bookingRoute = require("./booking-routes");
const redeemRoute = require("./redeem-routes");
const packageRoute = require("./package-routes");
const stripePaymentGateway = require('./stripe-payment-gateway');
const UserCards = require('./user-card-routes');
const GeneralRoutes = require('./general-routes');
const setupRoutes = (app) => {
    app.all("*", function (req, resp, next) {
        console.log("request header"); // do anything you want here
        console.log(req.headers); // do anything you want here
        console.log("request URL"); // do anything you want here
        console.log(req.path); // do anything you want here
        console.log("request Body"); // do anything you want here
        console.log(req.body); // do anything you want here
        next();
    });
    router.use("/login", loginRoutes);
    router.use("/forgot-password", forgotPasswordRoutes);
    router.use("/signup", signUpRoute);
    router.use("/refresh-token", refreshTokenRoute);
    router.use("/filter", filterRoute);
    router.use("/user", userRoute);
    router.use("/white-label", whiteLabelRoute);
    router.use("/company", companyRoute);
    router.use("/activity", activityRoute);
    router.use("/booking", bookingRoute);
    router.use("/package", packageRoute);
    router.use("/redeem", redeemRoute);
    router.use("/stripe/payment/gateway", stripePaymentGateway);
    router.use("/cards", UserCards);
    router.use("/general", GeneralRoutes);
    app.use("/", baseRoutes);
    app.use("/v1", router);
};

module.exports = {
    setupRoutes,
};
