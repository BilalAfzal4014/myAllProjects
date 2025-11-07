const router = require('express').Router();
const FilterModuleUseCase = require("../../usecases/filter/filter-module-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.get('/:type/:id?', (req, res) => {
    const interactor = new FilterModuleUseCase(req.params.type,req.params.id);
    interactor.fetchData()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

module.exports = router;
