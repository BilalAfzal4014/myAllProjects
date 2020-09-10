const ckout = require("../checkout-configurations/checkout-make-connection");
const axios = require("axios");

class checkoutService{

    getPaymentDetails(cko_session_id){

        return axios.get(`https://api.sandbox.checkout.com/payments/${cko_session_id}`, {
            headers: {
                Authorization: process.env.checkoutSecretKey,
                "Content-Type": "application/json"
            }
        }).then(function (response) {
            return response.data;
        }).catch(function (error) {
            return Promise.reject(error);
        });

    }

    makePayment(token){

        return ckout.payments.request({
            source: {
                // type:"token" is inferred
                token: token,
            },
            customer: {
                email: 'user@email.com',
                name: 'James Bond',
            },
            "3ds": {
                "enabled": true
            },
            currency: 'EUR',
            amount: 1000, // cents
            reference: 'ORDER123',
        }).then((response) => {
                return response;
            }).catch((error) => {
            return Promise.reject(error);
        });
    }
}

module.exports = new checkoutService();