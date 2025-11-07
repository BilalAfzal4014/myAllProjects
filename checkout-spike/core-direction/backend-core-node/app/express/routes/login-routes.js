const router = require('express').Router();
const LoginModuleUseCase = require("../../usecases/auth/login/login-module-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
const oAuthLogin = require("../../usecases/user/oAuth-signup/oauth-factory");

router.post('/:type', (req, res) => {
    const interactor = new LoginModuleUseCase(req.params.type, {...req.body, email: req.body.email.toLowerCase().trim()})
    interactor.login()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post('/social/oauth',(req, res) => {
    oAuthLogin(req.body)
        .then((user) => {
            res.status(200).json(user);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });
});


module.exports = router;
