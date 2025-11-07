const router = require('express').Router();
const RedeemApiUseCase = require("../../usecases/redeem/redeem-api-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const jwtVerification = require('../middlewares/jwt-verification/jwt-verification');
const {handleSingleImageUpload} = require("../multipart/multipart-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.get('/redeem-key/:key', jwtVerification(), (req, res) => {
    const interactor = new RedeemApiUseCase({'key': req.params.key, 'user_id': req.user_id});
    interactor.redeem()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
module.exports = router;
