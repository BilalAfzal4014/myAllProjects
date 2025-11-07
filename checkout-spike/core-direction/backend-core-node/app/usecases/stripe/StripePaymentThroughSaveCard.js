const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const StripeSaveUserInfoRepo = require("../../repositories/stripeSaveUserInfoRepo");
const ErrorTypes = require("../../errors/error-types");
const Validator = require("../../entity-validations/validator");
const StripeModuleEntity = require("../../entities/stripe-payment-gateway/stripe-module-entity")
const stripe = require('stripe')(process.env.stripe_api_key);
module.exports = class StripeFuturePaymentModuleUseCase extends BaseUseCase {
    constructor(payLoad) {
        super()
        this.payLoad = payLoad;
        this.stripeModuleEntityInstance = new StripeModuleEntity();
    }

    paymentThroughSaveCard(){
        return this.validate()
            .then((result) => {
                const stripeSaveCardInfo = StripeSaveUserInfoRepo.fetchCardByCustomerNameAndUserId(this.payLoad);
                return stripeSaveCardInfo.then((res)=>{
                    if(res.length === 0){
                        return this.sendErrorIfAnyForStripe([{
                            "message":"customer not found"
                        }])
                    }
                    const paymentThroughSaveCard =  stripe.paymentIntents.create({
                        amount: 200,
                        currency: 'usd',
                        customer: this.payLoad.customer,
                        payment_method: this.payLoad.payment_method,
                        error_on_requires_action: true,
                        confirm: true,
                        setup_future_usage: 'off_session',
                    });

                   return paymentThroughSaveCard.then((response)=>{
                       const payload = {
                            "user_id":this.payLoad.user_id,
                            "package_id":this.payLoad.package_id,
                            "charge_id":response.id,
                            "payment_status":"paid",
                            "user_stripe_info_id":res[0].id,
                            "payment_through":"save_card"
                        };
                        return  StripeSaveUserInfoRepo.saveUserStripePayment(payload)
                     });
                }).catch((error)=>{
                    return this.sendErrorIfAnyForStripe(error)
                });
            });
    }


    validate() {
        return this.validateFetchStripeCustomerInformation()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }
    validateFetchStripeCustomerInformation() {
        const payload = _.pick(this.payLoad, this.stripeModuleEntityInstance.getPaymentThroughSaveCardFields());
        this.validatorInstance = new Validator(payload, StripeSaveUserInfoRepo);
        return this.validatorInstance.validate(
            this.stripeModuleEntityInstance.getValidationRulesPaymentThroughSaveCard(),
            this.stripeModuleEntityInstance.getFieldsForUniquenessForSaveStripeCards(),
        );
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "",
            ""
        );
    }


    sendErrorIfAnyForStripe(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "",
            ""
        );
    }






}