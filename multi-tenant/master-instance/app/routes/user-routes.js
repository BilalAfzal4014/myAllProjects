const express = require("express");
const router = express.Router();

router.post("/login", function (req, res) {

    res.status(200).json({
        token:  req.body.company,
    })
});


router.post("/logout", function (req, res) {

    res.status(200).json({
        data: 'b'
    })
});

module.exports = router;