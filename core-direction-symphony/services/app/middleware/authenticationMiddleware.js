module.exports = async (req, res, next) => {
    const jwt = require('jsonwebtoken');
    const defaultResponse = require('./../helper/defaultResponse');
    const validation = require('./../middleware/validation');
    const {User, Apikey, AppVersion} = require('../config/config');
    var token = req.headers.authorizationusertoken;
    //  console.log('beforeRemoving', token);
    try {
        if (token) {
            if (token.startsWith("Bearer ")) {
                if (req.url == '/home') {
                    //   console.log('req',req.headers);
                    const credentials = auth(req);
                    const authValidations = validation.authValidation(credentials);
                    if (authValidations) {
                        const authValidationResponse = await validation.authApiValidation(credentials.name, credentials.pass);
                        if (authValidationResponse == null) {
                            throw 'Authorization credinational is not valid';
                        }
                    }
                }
                const Replacetoken = token.substring(7, token.length);
                //  console.log(token);
                jwt.verify(Replacetoken.trim(), process.env.PRIVATE_KEY, (err, decoded) => {
                    if (err) {
                        res.status(400).json(defaultResponse.createErrorResponse("401", "error", err));
                        //defaultResponse().error({message: req.t('USERS.ERROR_MESSAGE.INVALID_ACCESS_TOKEN')}, res, 401);
                        return;
                    }
                    // console.log('decoded', decoded);
                    req.userId = decoded.username;
                    req.token = decoded.token;
                    next();
                });
            } else {
                throw 'Bearer key is not added ';
            }
            // verifies secret and checks exp
        } else {
            if (req.url == '/login' || req.url == '/token' || req.url == '/email') {
                var headersValidation = validation.headerValidation(req);
                var headers = req.headers;
                var credentials = auth(req);
                var authValidations = validation.authValidation(credentials);
                if (headersValidation) {
                    console.log('headersValidation', headersValidation);
                    if (authValidations) {
                        const authValidationResponse = await validation.authApiValidation(credentials.name, credentials.pass);
                        if (authValidationResponse) {
                            const appVersionValidationResponse = await validation.appVersionValidation(headers.version, headers.devicetype);
                            if (appVersionValidationResponse) {
                                req.appId = appVersionValidationResponse.id;
                                if (headers.version < appVersionValidationResponse.version) {
                                    res.status(422).json(defaultResponse.createErrorResponse("400", "error", "version is not valid"));
                                }
                                next();
                            } else {
                                res.status(401).json(defaultResponse.createErrorResponse("401", "Unauthorized", "App version is not valid"));
                            }
                        } else {
                            res.status(401).json(defaultResponse.createErrorResponse("401", "Unauthorized", "Autherization not valid"));
                        }
                    } else {
                        throw 'Authorization is required';
                    }
                } else {
                    throw 'header is required';
                }
            } else {
                throw 'Url not found';
            }
        }
    } catch (err) {
        res.status(400).json(defaultResponse.createErrorResponse("400", "error", err));
    }

};