const router = require('express').Router();
const UserProfileModuleUseCase = require("../../usecases/user/fetch/get-user-module-usecase");
const UpdateProfileUseCase = require("../../usecases/user/update-profile-usecase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const jwtVerification = require('../middlewares/jwt-verification/jwt-verification');
const {handleSingleImageUpload} = require("../multipart/multipart-handler");
var makeLog = require('../middlewares/cloudwatch/cloudwatch');
router.get('/profile', jwtVerification(), (req, res) => {
    const interactor = new UserProfileModuleUseCase(req.user_id);
    interactor.fetchUserModule()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});

router.post('/update-profile', jwtVerification(), handleSingleImageUpload("image"), (req, res) => {
    if (req.file) {
        req.body = {...req.body, 'user_id': req.user_id, file: {...req.file}};
    } else {
        req.body = {...req.body, 'user_id': req.user_id,email: req.body.email.toLocaleLowerCase().trim()};
    }

    const interactor = new UpdateProfileUseCase(req.body);
    interactor.updateProfile()
        .then((result) => {
            makeLog(req, result, 'node-res')
            res.status(200).json({
                message: 'Profile updated successfully.'
            });
        }).catch((error) => {
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    });

});
module.exports = router;
