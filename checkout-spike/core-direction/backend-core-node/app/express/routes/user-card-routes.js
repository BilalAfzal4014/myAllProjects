const router = require('express').Router();
const FetchUserCardUseCase = require("../../usecases/user-payment-cards/fetch-user-card-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const jwtVerification = require("../middlewares/jwt-verification/jwt-verification");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');

router.get('/user-cards', jwtVerification(), (req, res) => {
    FetchUserCardUseCase.fetchAllCardsOfAUser(req.user_id)
        .then((result) => {
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.delete('/delete-user-card/:cardId', jwtVerification(), (req, res) => {
    FetchUserCardUseCase.deleteCardOfAUser(req.params.cardId, req.user_id)
        .then(() => {
            res.status(200).json();
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;

