const router = require('express').Router();
const RegisterModuleUseCase = require("../../usecases/auth/register/register-module-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
const Sentry = require("@sentry/node");
const SentryTracing = require("@sentry/tracing");
const transaction = Sentry.startTransaction({
    op: "test",
    name: "My First Test Transaction",
});
router.post('/', (req, res) => {
    const interactor = new RegisterModuleUseCase({...req.body, email: req.body.email.toLocaleLowerCase().trim()});
    try {
        interactor.register()
            .then((data) => {
                makeLog(req, data, 'node-res')
                res.status(200).json({
                    message: ' Please check your email to confirm'
                });
            }).catch((error) => {
            makeLog(req, error, 'node-error')
            return new HttpResponseHandler(res).handleError(error);
        })
    } catch (e) {
        Sentry.captureException(e);
    } finally {
        transaction.finish();
    }


});

module.exports = router;
