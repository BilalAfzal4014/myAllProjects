const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const stripe = require('stripe')(process.env.stripe_api_key);
const StripeSaveUserInfoRepo = require("../../repositories/stripeSaveUserInfoRepo");
const ErrorTypes = require("../../errors/error-types");
const Validator = require("../../entity-validations/validator");
const StripeModuleEntity = require("../../entities/stripe-payment-gateway/stripe-module-entity")

module.exports = class StripeFuturePaymentModuleUseCase extends BaseUseCase  {
    constructor(payLoad) {
        super()
        this.payLoad = payLoad;
        this.stripeModuleEntityInstance = new StripeModuleEntity();
    }

    initializeStripePaymentForFutureUse(){

        return new Promise((resolve,reject)=>{
            return resolve(this.createStripeCustomer());
        }).then((result)=>{
            return new Promise((resolve,reject)=>{
                const setupIntent = stripe.setupIntents.create({
                    customer: result.id,
                    payment_method_types: ['card'],
                });
                resolve(setupIntent);
            });
        })
    }
    createStripeCustomer(){
        return new Promise((resolve,reject)=>{
            return resolve(stripe.customers.create());
        });
    }
    saveSetupIntentInformationInDb(){
        return new Promise((resolve,reject)=>{
           return resolve(this.initializeStripePaymentForFutureUse());
        }).then((result)=>{
            const payload = _.pick(result,['id','client_secret','customer']);
            payload["setup_intent"] = payload.id;
            delete payload['id'];
            payload['user_id'] = this.payLoad.user_id;
            payload['payment_method_type'] = 'card'
            return StripeSaveUserInfoRepo.saveOrUpdateStripeFuturePayment(payload);
        });
    }

    fetchCustomerPaymentStatus(){
        return this.validate()
            .then((result) => {
                const customerInfo =  stripe.paymentMethods.list({
                    customer: this.payLoad.customer,
                    type: 'card',
                });
                return customerInfo.then((response) => {
                    if(response.data.length === 0){
                        return this.sendErrorIfAnyForStripe([{
                            "message": "customer id isn't verified."
                        }])
                    } else {
                        const payload = _.map(response.data, data => ({
                            "payment_method":data.id,
                            "city":data.billing_details.address.city,
                            "country":data.billing_details.address.country,
                            "state":data.billing_details.address.state,
                            "card_brand":data.card.brand,
                            "exp_month":data.card.exp_month,
                            "exp_year":data.card.exp_year,
                            "card_last_four_digit":data.card.last4,
                            "id":this.payLoad.id,
                            "is_client_secret_enabled":true,
                            "user_id":this.payLoad.user_id

                        }));
                        return StripeSaveUserInfoRepo.saveOrUpdateStripeFuturePayment(payload[0]);
                    }
                })
            });
    }
    validate() {
        return this.validateFetchStripeCustomerInformation()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }
    validateFetchStripeCustomerInformation() {
        const payload = _.pick(this.payLoad, this.stripeModuleEntityInstance.getStripeCardProvidedFields());
         this.validatorInstance = new Validator(payload, StripeSaveUserInfoRepo);
            return this.validatorInstance.validate(
                this.stripeModuleEntityInstance.getValidationRulesForSaveStripeCards(),
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

    sendErrorNotfound() {
        return this.handleErrorIfExist([
                {
                    "field": "",
                    "error": ""
                }
            ],
            ErrorTypes.NOT_FOUND,
            "",
            ""
        );
    }


    fetchCustomerCard(){
        return new Promise((resolve,reject)=>{
           resolve(StripeSaveUserInfoRepo.fetchSaveCard(this.payLoad));
        });
    }

    sendErrorIfAnyForStripe(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "",
            ""
        );
    }

    deleteCardInformation(){
        return this.validate()
            .then((result) => {
                const stripeSaveCardInfo = StripeSaveUserInfoRepo.fetchCardByCustomerNameAndUserId(this.payLoad);
                return stripeSaveCardInfo.then((res)=> {
                    if (res.length === 0) {
                        return this.sendErrorIfAnyForStripe([{
                            "message": "customer not found"
                        }])
                    }
                    const payload = {
                        "id":this.payLoad.id,
                        "deleted_at":new Date()
                    }
                    return StripeSaveUserInfoRepo.saveOrUpdateStripeFuturePayment(payload)
                })
            })
    }

}