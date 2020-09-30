const express = require("express");
const router = express.Router();

router.get("/a", function (req, res) {
    res.status(200).json({
        data: 'a'
    })
});


router.get("/b", function (req, res) {
    res.status(404).json({
        data: 'b'
    })
});

module.exports = router;
