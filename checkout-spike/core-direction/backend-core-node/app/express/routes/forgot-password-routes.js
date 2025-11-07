const router = require('express').Router();
const ForgotPasswordUseCase = require("../../usecases/auth/forgot-password/forgot-password-usecase");
const ConfirmLinkOfForgotPasswordUseCase = require("../../usecases/auth/forgot-password/confirm-link-of-forgot-password-usecase");
const ConfirmPasswordOfForgotPasswordUseCase = require("../../usecases/auth/forgot-password/confirm-password-of-forgot-password-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.post('/', (req, res) => {
    //see req.url
    const interactor = new ForgotPasswordUseCase({...req.body,email:req.body.email.toLocaleLowerCase().trim()}, req.protocol + "://" + req.get('host') + req.baseUrl)
    interactor.recoverPassword()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

// router.get('/', (req, res) => {
//     const interactor = new ConfirmLinkOfForgotPasswordUseCase(req.query.confirmationLink)
//     interactor.verifyConfirmationToken()
//         .then(() => {
//             res.render("templates/forgot-password/enter-new-password-template.ejs");
//         }).catch((error) => {
//         res.status(500).json(error);
//     });
// });
//
// router.post('/confirm-password/:confirmationLink', (req, res) => {
//     const interactor = new ConfirmPasswordOfForgotPasswordUseCase(req.params.confirmationLink, req.body)
//     interactor.savePassword()
//         .then(() => {
//             res.status(200).json();
//         }).catch((error) => {
//         return new HttpResponseHandler(res).handleError(error);
//     });
// });

module.exports = router;
