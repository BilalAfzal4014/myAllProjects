const queryString = require('query-string');
const SocialLoginUseCase = require("../social-login-usecase");
const fields = ["id", "first_name", "last_name", "picture"].join(",");
const {communicateViaAxios} = require("../../../../helpers/network-communication");

module.exports = class FacebookLoginUseCase extends SocialLoginUseCase {

    getUserInfo() {
        const request = this.prepareRequest();
        return communicateViaAxios(request)
            .then(({data}) => {
                return this.prepareResponseObj(data);
            });
    }

    prepareRequest() {
        const payLoad = {
            fields,
            access_token: this.data.access_token,
        };
        const requestData = {
            method: "POST",
            url: `https://graph.facebook.com/me?${queryString.stringify(payLoad)}`,
        };
        return requestData;
    }

    prepareResponseObj(data) {
        const email = `${data.id}@facebook.com`;
        return {
            id: data.id,
            first_name: data.first_name,
            last_name: data.last_name,
            email,
            username: email,
            need_to_verify_email: 0,
            type: "facebook"
        };
    }

};