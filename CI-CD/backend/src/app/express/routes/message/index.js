const express = require('express');
const HttpResponseHandler = require('../../../errors/handlers/httpErrorResponseHandler');
const SendMessageUseCase = require('../../../usecases/message/send')
const FetchMessageUseCase = require('../../../usecases/message/fetch')

const router = express.Router();

router.post('/send', (req, res) => {
    return (new SendMessageUseCase({...req.body, userId: +req.headers.identity})).send()
        .then((data) => {
            return res.status(200).json(data);
        }).catch((error) => {
            return new HttpResponseHandler(res).handleError(error);
        });
});

router.get('/:groupId', (req, res) => {
    return FetchMessageUseCase.fetchMessagesByGroupId(req.params.groupId)
        .then((data) => {
            return res.status(200).json(data);
        }).catch((error) => {
            return new HttpResponseHandler(res).handleError(error);
        });
});


module.exports = router;