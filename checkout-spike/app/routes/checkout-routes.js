const router = require("express").Router();
const checkoutServiceObj = require("../services/checkout-service");

router.get("/get-file", function (req, res) {

    let drinks = [
        {name: 'Bazoori', drunkness: 3},
        {name: 'Jam e shiri', drunkness: 5},
        {name: 'Rooh Afza', drunkness: 10}
    ];

    let tagline = "Any code of your own that you haven't looked at for six or more months might as well have been written by someone else.";

    res.render('checkout.ejs', {
        drinks: drinks,
        tagline: tagline,
        publicKey: process.env.checkoutPublicKey
    });

});

router.post("/make-payment", function (req, res) {

    checkoutServiceObj.makePayment(req.body.token)
        .then((response) => {
            return res.status(200).json(response);
        }).catch((error) => {
        return res.status(400).json(error);
    });

});

router.get("/payment-success", function (req, res) {

    checkoutServiceObj.getPaymentDetails(req.query["cko-session-id"])
        .then((response) => {
            return res.status(200).json(response);
        }).catch((error) => {
        return res.status(400).json(error);
    });
});

router.get("/payment-failure", function (req, res) {

    checkoutServiceObj.getPaymentDetails(req.query["cko-session-id"])
        .then((response) => {
            return res.status(200).json(response);
        }).catch((error) => {
        return res.status(400).json(error);
    });
});

module.exports = router;