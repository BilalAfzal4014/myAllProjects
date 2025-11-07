const router = require('express').Router();
const GetActivityUseCase = require("../../usecases/activity/get-activities-usecase");
const GetPackagesUseCase = require("../../usecases/activity/get-packages-usecase");
const BookActivityConfigurationUseCase = require("../../usecases/booking/book-activity/book-activity-configuration-usecase");
const MultipleAbsoluteBookingUseCase = require("../../usecases/booking/book-activity/multiple-booking/multiple-absolute-booking-usecase");
const CancelReserveActivityUseCase = require("../../usecases/booking/book-activity/cancel-reserve-activity/cancel-reserve-activity-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const jwtVerification = require("../middlewares/jwt-verification/jwt-verification");
const optionalJwtVerification = require("../middlewares/jwt-verification/optional-jwt-verification");
const makeLog = require('../middlewares/cloudwatch/cloudwatch');

router.post('/get-all-activities', jwtVerification(), (req, res) => {
    const interactor = new GetActivityUseCase({...req.body, user_id: req.user_id});
    interactor.fetchActivities()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.get('/get-packages/:id', optionalJwtVerification(), (req, res) => {

    const interactor = new GetPackagesUseCase({user_id: req.body.user_id, id: req.params.id});
    interactor.fetchPackages()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post('/book-activity', jwtVerification(), (req, res) => {

    const interactor = new BookActivityConfigurationUseCase({
        ...req.body,
        user_id: req.user_id,
        modified_by: req.user_id
    });
    interactor.bookActivity()
        .then(() => {
            makeLog(req, {}, 'node-res');
            res.status(200).json();
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post('/book-multiple-activity', jwtVerification(), (req, res) => {
    const interactor = new MultipleAbsoluteBookingUseCase({...req.body, user_id: req.user_id});
    interactor.bookActivity()
        .then((data) => {
            makeLog(req, {}, 'node-res');
            res.status(200).json(data);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.patch('/cancel-reserve-activity', jwtVerification(), (req, res) => {
    if (!req.body.member_ids) {
        req.body.member_ids = [req.user_id];
    }
    const interactor = new CancelReserveActivityUseCase({...req.body, user_id: req.user_id});
    interactor.cancelReserveActivity()
        .then(() => {
            makeLog(req, {}, 'node-res');
            res.status(200).json();
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;

