function routes() {
    const express = require('express');
    const router = express.Router();
    const userRoutes = require("../routes/userRoutes");
    const authenticationMiddleware = require("../middleware/authenticationMiddleware");
    router.use("/api/v1/user",authenticationMiddleware, userRoutes);
    return router;
}
module.exports = routes();