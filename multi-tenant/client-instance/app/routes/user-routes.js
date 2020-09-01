const express = require("express");
const router = express.Router();

router.post("/abc", function (req, res) {

    res.status(200).json({
        data: 'a'
    });

});


router.post("/xyz", function (req, res) {

    res.status(404).json({
        data: 'b'
    });

});

module.exports = router;