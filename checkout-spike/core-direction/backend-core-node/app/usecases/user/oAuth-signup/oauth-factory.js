const GoogleLoginUseCase = require('./impl/google-login-usecase');
const FacebookLoginUseCase = require('./impl/fb-login-usecase');
//const AppleLoginUseCase = require('./impl/apple-login-usecase');

const OAUTH_TYPES = require('./oauth-types');

class OAuthFactory {
    static getInstance = (data) => {
        switch (data.type) {
            case OAUTH_TYPES.FACEBOOK:
                return new FacebookLoginUseCase(data);
            case OAUTH_TYPES.GOOGLE:
                return new GoogleLoginUseCase(data);
            case OAUTH_TYPES.APPLE:
                return new GoogleLoginUseCase(data);
            default:
                throw new Error(`OAuth [${type}] is not supported`);
        }
    };
}

function oAuthLogin(data){
    return OAuthFactory.getInstance(data).login();
}

module.exports = oAuthLogin;
