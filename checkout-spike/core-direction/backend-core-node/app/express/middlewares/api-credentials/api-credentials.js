const Base64Helper = require("../../../helpers/base-64-helper");
module.exports = apiCredentials = () => {

    return (req, res, next) => {
        const credentials = fetchBasicAuthCredentialsFromRequest(req);
        req.api_credentials = {...credentials};
        next();
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