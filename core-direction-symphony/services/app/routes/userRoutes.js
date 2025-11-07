function userRoutes() {
    const express = require("express");
    const userRoutes = express.Router();
    const userController = require("../controllers/userController");
    const homeController = require("../controllers/homeController");
    userRoutes.post("/login", (req, res) => {
        userController(req).login(res);
    });
    userRoutes.post("/token", (req, res) => {
        userController(req).userToken(res);
    });
    userRoutes.post("/email", (req, res) => {
        userController(req).emailLogin(res);
    });
    userRoutes.post("/forgetPassword", (req, res) => {
        userController(req).forgotPassword(res);
    });
    userRoutes.post("/home", (req, res) => {
        homeController(req).home(res);
    });
    return userRoutes;
}

module.exports = userRoutes();
