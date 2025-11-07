const express = require('express');
const HttpResponseHandler = require('../../../errors/handlers/httpErrorResponseHandler');
const AuthenticateUserUseCase = require('../../../usecases/user/authenticate');
const FetchUserUseCase = require('../../../usecases/user/fetch');

const router = express.Router();

router.post('/authenticate', (req, res) => {
    console.log('is there any value', process.env.conflict);
    console.log(process.env.docker);
    return (new AuthenticateUserUseCase(req.body)).authenticate()
        .then((data) => {
            return res.status(200).json(data);
        }).catch((error) => {
            return new HttpResponseHandler(res).handleError(error);
        });
});


router.get('/listing', (req, res) => {
    return FetchUserUseCase.getAllUsersExceptCurrent(req.headers.identity)
        .then((data) => {
            return res.status(200).json(data);
        }).catch((error) => {
            return new HttpResponseHandler(res).handleError(error);
        });
});

module.exports = router;