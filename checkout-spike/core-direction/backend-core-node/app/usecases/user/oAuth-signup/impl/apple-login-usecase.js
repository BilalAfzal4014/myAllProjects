const SocialLoginUseCase = require("../social-login-usecase");
const JwtHelper = require("../../../../helpers/jwt-helper");
const JwksClient = require("../../../../helpers/jwks-client");
const ErrorTypes = require("../../../../errors/error-types");


const options = {
    jwksUri: 'https://appleid.apple.com/auth/keys'
}

module.exports = class AppleLoginUseCase extends SocialLoginUseCase {

    getUserInfo() {
        const client = JwksClient.getClient(options);
        const jsonDataOfToken = JwtHelper.decodeToken(this.data.access_token, true);
        return this.fetchPublicKeyToVerifyTokenFromApple(client, jsonDataOfToken.header.kid)
            .then((appleProvidedPublicKey) => {
                return this.verifyTokenWithAppleProvidedPublicKey(appleProvidedPublicKey)
            }).then((user) => {
                return this.prepareResponseObj(user);
            });
    }

    fetchPublicKeyToVerifyTokenFromApple(client, kid) {
        return JwksClient.getPublicKeyFromSigningKey(client, kid);
    }

    verifyTokenWithAppleProvidedPublicKey(appleProvidedPublicKey) {
        return JwtHelper.verifyToken(this.data.access_token, appleProvidedPublicKey)
            .then((token) => {
                return this.verifyIfTokenBelongsToOurPackage(token);
            });
    }

    verifyIfTokenBelongsToOurPackage(token) {
        return process.env.SIGN_UP_WITH_APPLE_PACKAGE_NAME === token.aud ? token : this.handleError(
            [],
            ErrorTypes.NOT_FOUND,
            "This token doesn't belong to this application",
            "BusinessError from verifyIfTokenBelongsToOurPackage function in AppleLoginUseCase"
        );
    }

    prepareResponseObj(user) {
        return {
            first_name: user.email,
            last_name: "",
            email: user.email,
            username: user.email
        };
    }
}
