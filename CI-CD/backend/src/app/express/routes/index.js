const express = require('express');
const userRoutes = require('./user');
const messageRoutes = require('./message');
const verifyIdentity = require('../middlewares/Identity');

const setupRoutes = (app) => {
    const router = express.Router();
    router.use("/users", userRoutes);
    router.use("/messages", messageRoutes);
    app.use("/v1", verifyIdentity(), router);
};

module.exports = {
    setupRoutes
};