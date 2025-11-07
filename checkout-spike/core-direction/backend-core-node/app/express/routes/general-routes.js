const router = require('express').Router();
const ContactUs = require("../../usecases/general/contact_us/contact-us");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.post('/contact-us', (req, res) => {
    const interactor = new ContactUs(req.body);
    interactor.sendMail()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});


module.exports = router;
