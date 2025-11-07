const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const StripeSaveUserInfoRepo = require("../../repositories/stripeSaveUserInfoRepo");
const ErrorTypes = require("../../errors/error-types");
const Validator = require("../../entity-validations/validator");
const StripeModuleEntity = require("../../entities/stripe-payment-gateway/stripe-module-entity")
const stripe = require('stripe')(process.env.stripe_api_key);


module.exports = class StripeCheckoutUseCase extends BaseUseCase {
    constructor(payLoad) {
        super()
        this.payLoad = payLoad;
        this.stripeModuleEntityInstance = new StripeModuleEntity();
    }

    stripeCheckout(){
        // package_name,image,amount,quantity

        const result = stripe.checkout.sessions.create({
            payment_method_types:['card'],
            line_items:[{
                price_data:{
                    currency:"usd",
                    product_data:{
                        name:"Hello"
                        // images:this.payLoad.package_image
                    },
                    unit_amount:300
                },
                quantity:4
            }],
            mode:"payment",
            success_url:`${process.env.customer_app_url}/success?id={CHECKOUT_SESSION_ID}`,
            cancel_url:`${process.env.customer_app_url}/cancel.html`
        });
        return result.then((response) => {
            const payload = {};
            payload["session_id"] = response.id;
            payload["payment_through"]= "checkout";
            payload["payment_status"]=response.payment_status;
            payload["user_id"] = this.payLoad.user_id;
            payload["package_id"]=1;
            payload["cancel_url"] = response.cancel_url;
            payload["success_url"] = response.success_url;
            payload["payment_intent"] = response.payment_intent;
            return  StripeSaveUserInfoRepo.saveUserStripePayment(payload)

        })




    }
}