const router = require('express').Router();
const UserBookingHistoryUseCase = require("../../usecases/booking/get-user-booking-history-usecase");
const UserUpComingBookingUseCase = require("../../usecases/booking/get-user-upcoming-booking-usecase");
const UserCalendarBookingUseCase = require("../../usecases/booking/get-user-calendar-booking-usecase");
const BookReserveActivityUseCase = require("../../usecases/booking/book-reserve-activity-usecase");
const CheckinActivityUsecase = require("../../usecases/booking/checkin-activity-usecase");
const BulkBookActivityUseCase = require("../../usecases/booking/bulk-book-activity-usecase");
const CancelActivityUseCase = require("../../usecases/booking/cancel-activity-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const jwtVerification = require('../middlewares/jwt-verification/jwt-verification');
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.get('/get-user-booking-history', jwtVerification(), (req, res) => {

    const interactor = new UserBookingHistoryUseCase(req);
    interactor.fetchData()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.get('/get-user-upcoming-booking', jwtVerification(), (req, res) => {

    const interactor = new UserUpComingBookingUseCase(req);
    interactor.fetchData()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.get('/get-user-calendar-booking', jwtVerification(), (req, res) => {

    const interactor = new UserCalendarBookingUseCase(req);
    interactor.fetchData()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.post('/checkin', jwtVerification(), (req, res) => {

    const interactor = new CheckinActivityUsecase(req);
    interactor.cancelActivity()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json({"message": "Checkin Successful"});
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post('/book-reserve-activity', jwtVerification(), (req, res) => {

    const interactor = new BookReserveActivityUseCase({...req.body, user_id: req.user_id});
    interactor.bookReserveActivity()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.post('/bulk-user-book-activity', jwtVerification(true), (req, res) => {

    const interactor = new BulkBookActivityUseCase({...req.body, user_id: req.user_id});
    interactor.bookActivity()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post('/cancel-activity', jwtVerification(), (req, res) => {

    const interactor = new CancelActivityUseCase({...req.body, user_id: req.user_id});
    interactor.cancelActivity().then((result) => {
        makeLog(req, result, 'node-res')
        res.status(200).json(result);
    }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
module.exports = router;
