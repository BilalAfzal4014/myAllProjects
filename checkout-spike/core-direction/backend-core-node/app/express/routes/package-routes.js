const router = require('express').Router();
const GetMemberPackagesUseCase = require("../../usecases/package/get-member-packages-usecase");
const PurchasePackageUseCase = require("../../usecases/package/purchase-package-usecase");
const GetClassMemberPackagesUseCase = require("../../usecases/package/get-class-member-packages-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const jwtVerification = require("../middlewares/jwt-verification/jwt-verification");
const makeLog = require('../middlewares/cloudwatch/cloudwatch');
const packageUsecase = require('../../usecases/package/get-package');
const PurchaseMultiplePackageUseCase = require("../../usecases/package/purchase-multiple-package-usecase");

router.post('/get-member-packages', jwtVerification(), (req, res) => {
    const interactor = new GetMemberPackagesUseCase({...req.body, user_id: req.user_id});
    interactor.fetchMemberPackages()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post("/purchase-package", jwtVerification(), function (req, res) {
    //req.user_id = 198;
    const interactor = new PurchasePackageUseCase({
        ...req.body, user_id: req.user_id
    });
    interactor.purchase()
        .then((memberPackage) => {
            makeLog(req, memberPackage, 'node-res')
            res.status(200).json(memberPackage);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });
});

router.post("/purchase-multiple-packages", jwtVerification(), function (req, res) {
    const interactor = new PurchaseMultiplePackageUseCase({
        ...req.body, user_id: req.user_id
    });
    interactor.purchaseMultiplePackages()
        .then((memberPackage) => {
            makeLog(req, memberPackage, 'node-res')
            res.status(200).json(memberPackage);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });
});

router.get('/get-class-member-packages/:id', jwtVerification(), (req, res) => {
    const interactor = new GetClassMemberPackagesUseCase({'class_id': req.params.id, 'member_id': req.user_id});
    interactor.fetchClassMemberPackages()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.get('/get-package/:id', jwtVerification(), (req, res) => {
    const interactor = new packageUsecase({'id': req.params.id, 'member_id': req.user_id});
    interactor.getPackageDetail()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;

