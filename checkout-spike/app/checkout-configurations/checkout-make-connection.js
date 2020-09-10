const {Checkout} = require('checkout-sdk-node');

const cko = new Checkout( process.env.checkoutSecretKey, {
    pk: process.env.checkoutPublicKey,
    timeout: 7000
});

module.exports = cko;
