const ApiKeyRepo = require("../../../repositories/apiKeyRepo");
const BusinessError = require("../../../errors/business-error");
const ErrorTypes = require('../../../errors/error-types');
const Base64Helper = require("../../../helpers/base-64-helper");
const GeneralConstant = require("../../../constants/general");

module.exports = basicAuth = () => {
    return (req, res, next) => {
        if(GeneralConstant.ALLOWED_ROUTES.includes(req.path)){

            return next();
        }
        const credentials = fetchBasicAuthCredentialsFromRequest(req);
        let error = null;
        if (credentials) {
            return validateFromApiKey(credentials)
                .then((validate) => {
                    if (!validate)
                        error = getBasicAuthError(ErrorTypes.NOT_FOUND, "Basic Authentication failed");
                    next(error);
                });
        } else {
            error = getBasicAuthError(ErrorTypes.FORBIDDEN, "Basic Authentication required");
            next(error);
        }

    }
}

function fetchBasicAuthCredentialsFromRequest(req) {
    if (!req.headers.authorization)
        return;

    const base64EncodedCredentialsWithBasicStr = req.headers.authorization;
    const base64EncodedCredentialsWithoutBasicStr = base64EncodedCredentialsWithBasicStr.substr(6, base64EncodedCredentialsWithBasicStr.length - 1);
    let base64DecodedCredentials = Base64Helper.base64Decode(base64EncodedCredentialsWithoutBasicStr);
    base64DecodedCredentials = base64DecodedCredentials.split(":");

    return {
        username: base64DecodedCredentials[0],
        password: base64DecodedCredentials[1]
    };
}

function validateFromApiKey(credentials) {
    return ApiKeyRepo.findByApiKeyAndPassword(
        credentials.username,
        credentials.password
    ).then((records) => {
        return records.length;
    });
}

function getBasicAuthError(status, errorMessage) {
    return new BusinessError(
        status,
        errorMessage, [],
        "BusinessError from basic auth middleware"
    );
}