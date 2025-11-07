const router = require('express').Router();
const WhiteLabelSettingUseCase = require("../../usecases/white-label/get-white-label-settings-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const apiCredentials = require('../middlewares/api-credentials/api-credentials');
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.get('/settings', apiCredentials(), (req, res) => {
    const interactor = new WhiteLabelSettingUseCase(req.api_credentials);
    interactor.fetchWhiteLabelSetting()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;
