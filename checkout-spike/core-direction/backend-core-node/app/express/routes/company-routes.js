const router = require('express').Router();
const GetCompaniesUseCase = require("../../usecases/company/get-companies-usecase");
const CompanyDetailUseCase = require("../../usecases/company_detail/company-detail-usecase");
const GetCompanyPackagesUseCase = require("../../usecases/company_detail/company-packages-usecase");
const optionalJwtVerification = require("../middlewares/jwt-verification/optional-jwt-verification");
const FacilityCompanyUseCase = require("../../usecases/company_detail/facility-company-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.post('/get-companies', (req, res) => {
    const interactor = new GetCompaniesUseCase(req.body);
    interactor.fetchCompanies()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.post('/get-company-detail', optionalJwtVerification(), (req, res) => {
    const interactor = new CompanyDetailUseCase(req.body);
    interactor.fetchCompanyDetail()
        .then((result) => {
            // makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.post('/packages/:id', (req, res) => {

    const interactor = new GetCompanyPackagesUseCase(req.params.id);
    interactor.fetchCompanyPackages()
        .then((result) => {
            // makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
router.get('/get-facility-companies', (req, res) => {

    const interactor = new FacilityCompanyUseCase();
    interactor.fetchData()
        .then((result) => {
            // makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;
