const router = require('express').Router();
const RefreshTokenUseCase = require("../../usecases/auth/refresh-token/refresh-token-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.post('/', (req, res) => {
    const interactor = new RefreshTokenUseCase(req.body)
    interactor.getJwtToken()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;
