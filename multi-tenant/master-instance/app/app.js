const express = require("express");
const app = express();
const userRoutes = require("./routes/user-routes");
const someRoutes = require("./routes/some-routes");
const makeSequelizeTenantConnection = require("./dbs-config/db-sequelize-config");
const makeMongoTenantConnection = require("./dbs-config/db-mongo-config");

let sequelizeConnectionPool = {};
let mongoConnectionPool = {};

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
    if (req.headers.authorization) {

        if (sequelizeConnectionPool[req.headers.authorization] === undefined || mongoConnectionPool[req.headers.authorization] === undefined) {
            let query = 'select * from tenant_mapping where org_name = :orgName';
            global.masterDB.query(
                query, {
                    replacements: {
                        orgName: req.headers.authorization,
                    },
                    type: masterDB.QueryTypes.SELECT
                }
            ).then((result) => {

                if (sequelizeConnectionPool[req.headers.authorization] === undefined) {
                    sequelizeConnectionPool[req.headers.authorization] = makeSequelizeTenantConnection(result[0].db_name, result[0].db_user_name, result[0].db_password, result[0].db_url_read, result[0].db_port);
                }

                if (mongoConnectionPool[req.headers.authorization] === undefined) {
                    mongoConnectionPool[req.headers.authorization] = makeMongoTenantConnection(result[0].db_url_read);
                }

                req.body.sequelizeTenantConnection = sequelizeConnectionPool[req.headers.authorization];
                req.body.mongoTenantConnection = mongoConnectionPool[req.headers.authorization];
                next();
            }).catch((error) => {
                console.log(error);
            });
        } else {
            req.body.sequelizeTenantConnection = sequelizeConnectionPool[req.headers.authorization];
            req.body.mongoTenantConnection = mongoConnectionPool[req.headers.authorization];
            next();
        }

    } else {
        res.status(404).json({
            message: "login-first"
        })
    }
});

app.use("/authenticated", someRoutes);

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