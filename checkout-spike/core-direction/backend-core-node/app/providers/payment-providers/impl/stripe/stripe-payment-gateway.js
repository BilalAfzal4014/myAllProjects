const BasePayment = require("../base-payment");
const Stripe = require("./index");
const {v4: uuid} = require("uuid");

module.exports = class StripPaymentGateway extends BasePayment {
    constructor(payLoad) {
        super(payLoad);
        this.stripCustomer = null;
    }

    chargePaymentWithTokenizeCreditCard() {
        return this.createStripeCustomer()
            .then(() => {
                return this.createStripeChargeWithToken();
            }).then((charge) => {
                return this.transformDateForSuccessFullPayment(charge);
            }).catch((error) => {
                return this.transformDateForFailedPayment(error);
            });
    }

    createStripeCustomer() {
        return Stripe.customers.create({
            email: this.payLoad.token?.email, // email coming from strip form
            name: this.payLoad.user_info?.username, //actual user email
            source: this.payLoad.token?.id
        }).then((customer) => {
            this.stripCustomer = customer;
        });
    }

    createStripeChargeWithToken() {
        return Stripe.charges.create(
            {
                amount: this.payLoad.total_payment * 100,
                currency: this.payLoad.currency,
                customer: this.stripCustomer.id,
                receipt_email: this.payLoad.token.email,
                description: `Purchased the ${this.payLoad.product}`,
                shipping: {
                    name: this.payLoad.token?.card?.name,
                    address: {
                        line1: this.payLoad.token?.card?.address_line1,
                        line2: this.payLoad.token?.card?.address_line2,
                        city: this.payLoad.token?.card?.address_city,
                        country: this.payLoad.token?.card?.address_country,
                        postal_code: this.payLoad.token?.card?.address_zip
                    }
                }
            },
            {
                idempotencyKey: uuid(),
            }
        );
    }

    chargePaymentWithCardTrackingId() {
        return this.createStripeChargeWithCustomerId()
            .then((charge) => {
                return this.transformDateForSuccessFullPayment(charge);
            }).catch((error) => {
                return this.transformDateForFailedPayment(error);
            });
    }

    createStripeChargeWithCustomerId() {
        return Stripe.charges.create(
            {
                amount: this.payLoad.total_payment * 100,
                currency: this.payLoad.currency,
                customer: this.payLoad.user_card_details.save_card_tracking_id,
                //receipt_email: this.payLoad.token.email,
                description: `Purchased the ${this.payLoad.product}`,
            },
            {
                idempotencyKey: uuid(),
            }
        );
    }

    transformDateForSuccessFullPayment(charge) {
        return {
            user_id: this.payLoad.user_info?.id,
            charge_id: charge.id,
            track_id: charge.source.id,
            card_id: charge.source.id,
            save_card_track_id: charge.customer,
            last_four: charge.payment_method_details.card.last4,
            vat: null,
            amount: charge.amount_captured / 100,
            currency: charge.currency,
            transaction_response: JSON.stringify(charge),
            transaction_type: "Payment",
            status: "success",
            payment_gateway_used: "stripe",
            failure_code: "N/A",
            product_purchased: this.payLoad.product,
            card_holder_name: this.payLoad.token?.card?.name,
            card_type: this.payLoad.token?.card?.brand
        };
    }

    transformDateForFailedPayment(error) {
        return {
            user_id: this.payLoad.user_info?.id,
            charge_id: error.requestId,
            track_id: error.requestId,
            card_id: error.requestId,
            save_card_track_id: "N/A",
            last_four: "N/A",
            vat: null,
            amount: this.payLoad.total_payment,
            currency: this.payLoad.currency,
            transaction_response: JSON.stringify(error),
            transaction_type: "Payment",
            status: "fail",
            payment_gateway_used: "stripe",
            failure_code: error.raw.code,
            product_purchased: this.payLoad.product,
            card_holder_name: this.payLoad.token?.card?.name,
            card_type: this.payLoad.token?.card?.brand
        };
    }
}