const SocialLoginUseCase = require("../social-login-usecase");
const {OAuth2Client} = require("google-auth-library");

const CLIENTS_SECRET = {
    web: process.env.google_client_secret_for_web
};

module.exports = class GoogleLoginUseCase extends SocialLoginUseCase {

    getUserInfo() {
        const clientSecret = CLIENTS_SECRET[this.data.client];
        const oauthClient = new OAuth2Client(clientSecret);
        return oauthClient.verifyIdToken({
            idToken: this.data.access_token,
            clientSecret
        }).then(({payload}) => {
            return this.prepareResponseObj(payload);
        });
    }

    prepareResponseObj(dataFromGoogle) {
        return {
            id: dataFromGoogle.sub,
            first_name: dataFromGoogle.given_name,
            last_name: dataFromGoogle.family_name,
            email: dataFromGoogle.email,
            username: dataFromGoogle.email,
            need_to_verify_email: 0,
            type: "google"
        };
    }

};