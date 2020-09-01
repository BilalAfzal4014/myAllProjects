const express = require("express");
const router = express.Router();
const getUserDelModel = require("../models/sequelize/user-del");
const getUserStatsModelMongo = require("../models/mongo/user-stats");

router.get("/a-route", function (req, res) {

    let query = 'select * from tenant_mapping';
    global.masterDB.query(
        query, {
            type: masterDB.QueryTypes.SELECT
        }
    ).then((result) => {
        res.status(200).json({
            data: result
        })
    }).catch((error) => {

    });


});


router.get("/b-route", function (req, res) {

    let query = 'select * from user_dels';
    req.body.sequelizeTenantConnection.query(
        query, {
            type: req.body.sequelizeTenantConnection.QueryTypes.SELECT
        }
    ).then((result) => {
        res.status(200).json({
            data: result
        })
    }).catch((error) => {

    });

});

router.get("/c-route", async function (req, res) {

    let UserDel = getUserDelModel(req.body.sequelizeTenantConnection);
    let user = await UserDel.findAll();

    res.status(200).json({
        data: user[0].dataValues
    })

});

router.get("/d-route", async function (req, res) {

    let UserStats = getUserStatsModelMongo(req.body.mongoTenantConnection);
    let stats = await UserStats.find({});

    res.status(200).json({
        data: stats
    })

});

module.exports = router;