const ckout = require("../checkout-configurations/checkout-make-connection");
const axios = require("axios");

class checkoutService {

    getPaymentDetails(cko_session_id) {
        // both api's are returning the samething
        let p1 = axios.get(`https://api.sandbox.checkout.com/payments/${cko_session_id}`, {
            headers: {
                Authorization: process.env.checkoutSecretKey,
                "Content-Type": "application/json"
            }
        }).then(function (response) {
            return response.data;
        }).catch(function (error) {
            return Promise.reject(error);
        });

        let p2 = ckout.payments.get(cko_session_id)
            .then(function (response) {
                return response;
            }).catch(function (error) {
                return Promise.reject(error);
            });

        return Promise.all([p1, p2].map((p) => {
            return p.catch(e => e)
        })).then((result) => {
            return result;
        }).catch((error) => {
            return Promise.reject(error);
        });

    }

    makePayment(token) {

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
                enabled: true,
                attempt_n3d: true //if card is not 3ds enabled then payment flow will not be 3ds and additionally in the response of 3ds enabled card the redirectLink prop will contain 3ds2 inside it
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