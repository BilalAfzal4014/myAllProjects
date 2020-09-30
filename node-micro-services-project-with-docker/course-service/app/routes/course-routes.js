const express = require("express");
const router = express.Router();
const mysqlConnection = require("../database-connection-configuration/database-connection");

router.get("/get-courses", function (req, res) {

    let query = `select *`;
    query += ` from courses`;

    mysqlConnection.query(query, function (error, result, fields) {
        if (!error) {
            res.status(200).json({
                status: 200,
                data: result,
                message: `Courses listing`
            });
        }
    });

});


router.get("/get-student-courses/:userId", function (req, res) {

    let query = `select courses.id, courses.name`;
    query += ` from users join user_course_mappings on users.id = user_course_mappings.user_id`;
    query += ` join courses on user_course_mappings.course_id = courses.id`;
    query += ` where users.id = ${mysqlConnection.escape(req.params.userId)}`;

    mysqlConnection.query(query, function (error, result, fields) {
        if (!error) {
            res.status(200).json({
                status: 200,
                data: result,
                message: `Courses of a user: ${req.params.userId}`
            });
        }
    });

});

module.exports = router;
