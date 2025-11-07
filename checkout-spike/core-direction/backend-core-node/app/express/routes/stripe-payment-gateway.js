const StripeFuturePaymentModuleUseCase = require("../../usecases/stripe/StripeFuturePaymentModuleUseCase");
const StripePaymentThroughSaveCard = require("../../usecases/stripe/StripePaymentThroughSaveCard");
const StripeCheckoutUseCase = require("../../usecases/stripe/StripeCheckoutUseCase");
const HttpResponseHandler = require("../../errors/handlers/http-error-response-handler");
const makeLog = require('../middlewares/cloudwatch/cloudwatch');
const router = require('express').Router();

router.get('/card/initialize',jwtVerification(),(req,res)=>{
        const interactor = new StripeFuturePaymentModuleUseCase({...req.body, user_id: req.user_id});
        interactor.saveSetupIntentInformationInDb()
        .then((result)=>{
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        })
        .catch((error)=>{
            makeLog(req, error, 'node-error')
            return new HttpResponseHandler(res).handleError(error);
        })


});

router.post('/save/card/information',jwtVerification(),(req,res)=>{
    const interactor = new StripeFuturePaymentModuleUseCase({...req.body, user_id: req.user_id});
    interactor.fetchCustomerPaymentStatus()
        .then((result)=>{
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        })
        .catch((error)=>{
            makeLog(req, error, 'node-error')
            return new HttpResponseHandler(res).handleError(error);
        });

});

router.get('/card/lists',jwtVerification(),(req,res)=>{
    const interactor = new StripeFuturePaymentModuleUseCase({...req.body, user_id: req.user_id});
    interactor.fetchCustomerCard()
        .then((result)=>{
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        })
        .catch((error)=>{
            makeLog(req, error, 'node-error')
            return new HttpResponseHandler(res).handleError(error);
        });

});


router.post('/payment/save/card',jwtVerification(),(req,res)=>{
    const interactor = new StripePaymentThroughSaveCard({...req.body, user_id: req.user_id});
    interactor.paymentThroughSaveCard()
        .then((result)=>{
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error)=>{
            makeLog(req, error, 'node-error')
            return new HttpResponseHandler(res).handleError(error);
        })
});

router.delete('/delete/card',jwtVerification(),(req,res)=>{
    const interactor = new StripeFuturePaymentModuleUseCase({...req.body, user_id: req.user_id});
    interactor.deleteCardInformation()
        .then((result)=>{
            makeLog(req, result, 'node-res')
            res.status(200).json({"message": "Card information has been deleted successfully."});
        }).catch((error)=>{
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    })
});

router.post('/checkout',jwtVerification(),(req,res)=>{
    const interactor = new StripeCheckoutUseCase({...req.body, user_id: req.user_id});
    interactor.stripeCheckout()
        .then((result)=>{
            makeLog(req, result, 'node-res')
            res.status(200).json(result);
        }).catch((error)=>{
        makeLog(req, error, 'node-error')
        return new HttpResponseHandler(res).handleError(error);
    })
});
module.exports = router;