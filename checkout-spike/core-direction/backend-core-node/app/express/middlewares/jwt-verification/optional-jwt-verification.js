const VerifyJWTUseCase = require("../../../usecases/jwt/verify-jwt-usecase");
const BusinessError = require("../../../errors/business-error");
const ErrorTypes = require('../../../errors/error-types');
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
module.exports = optionalJwtVerification = (userObject = false) => {
    return (req, res, next) => {
        let error = null;
        if ([process.env.jwt_token_header_name] in req.headers) {
        } else {
            req.body = {...req.body,user_id: 0};
            return next();
        }
        const jwtTokenWithBearer = req.headers[process.env.jwt_token_header_name];

        if (!hasToken(jwtTokenWithBearer)) {
            next(getJwtTokenNotFoundError());
            return;
        }
        const jwtToken = getJwtTokenWithOutBearer(jwtTokenWithBearer);

        return VerifyJWTUseCase.verifyTokenGivenPublicKeyPath(jwtToken)
            .then((decodedToken) => {
                return getUserDetails(decodedToken.token);
            }).then((user) => {
                if (userObject) {
                    req.user_id = user;
                } else {
                    req.body = {...req.body,user_id: user.id};
                }
                next();
            }).catch((err) => {
                if (err.message == 'invalid token')
                    next(getInvalidJwtTokenError());
                else
                    next(getJwtTokenLoginOnOtherDeviceError());
            });
    }
}


function hasToken(token) {
    return token;
}

async function getUserDetails(token) {

    let user = await FosUserUserRepo.findByToken(token);
    if (user) {
        return user;
    } else {
        throw new Error();
    }
}

function getInvalidJwtTokenError() {
    return new BusinessError(
        ErrorTypes.FORBIDDEN,
        "Jwt token verification failed",
        [],
        "BusinessError from jwt verification middleware"
    );
}

function getJwtTokenLoginOnOtherDeviceError() {
    return new BusinessError(
        ErrorTypes.DUPLICATE_DATA,
        "You have been login on other device please relogin",
        [],
        "BusinessError from jwt verification middleware"
    );
}

function getJwtTokenWithOutBearer(jwtTokenWithBearer) {
    return jwtTokenWithBearer.split(' ').pop();
}


function getJwtTokenNotFoundError() {
    return new BusinessError(
        ErrorTypes.NOT_FOUND,
        "Full authorization is required to access this route",
        [],
        "BusinessError from jwt verification middleware"
    );
}
