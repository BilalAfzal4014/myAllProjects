const StripePaymentGateway = require('./impl/stripe/stripe-payment-gateway');
const ProviderTypes = require('./provider-types');

let provider = null;
const getPaymentProvider = () => {
    if (null === provider) {
        const type = process.env.PAYMENT_PROVIDER_TYPE || ProviderTypes.STRIPE;
        switch (type) {
            case ProviderTypes.STRIPE:
                provider = StripePaymentGateway;
                break;
            default:
                throw new Error("Could not find suitable payment provider");
        }
    }
    return provider;
};

module.exports = {
    getPaymentProvider
}