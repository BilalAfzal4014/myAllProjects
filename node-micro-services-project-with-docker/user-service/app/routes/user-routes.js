const express = require("express");
const router = express.Router();
const axios = require("axios");
const mysqlConnection = require("../database-connection-configuration/database-connection");

router.get("/get-users", function (req, res) {

    let query = `select *`;
    query += ` from users`;

    mysqlConnection.query(query, function(error, result, fields){
        if(!error){
            res.status(200).json({
                status: 200,
                data: result,
                message: "Users listing"
            });
        }
    });

});

router.get("/get-student-courses/:userId", function (req, res) {

    let url = `http://gateway-container:3000/course-service/get-student-courses/${req.params.userId}`;
    //let url = `http://course-service-container:3000/get-student-courses/${req.params.userId}`;
    //we can also call the course-service directly without calling the gateway, but this is just for testing
    axios.get(url, {}, {})
        .then((result) => {
            return res.status(result.data.status).json(result.data);
        }).catch((error) => {
        return res.status(500).json({
            status: 500,
            message: "Something went wrong"
        });
    });
});

module.exports = router;
