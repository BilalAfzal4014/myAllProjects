const jwt = require('jsonwebtoken');
const FileHelpers = require("../../helpers/file-helpers");

module.exports = class VerifyJWTUseCase {


    static verifyTokenGivenPublicKeyPath(token, privateKeyPath = process.env.private_key_path_for_jwt) {
        return VerifyJWTUseCase.readPublicKey(privateKeyPath)
            .then((privateKEY) => {
                return VerifyJWTUseCase.verifyTokenGivenPublicKey(token, privateKEY);
            });
    }

    static readPublicKey(privateKeyPath) {
        return FileHelpers.readFileFromGivenPath(privateKeyPath);
    }

    static verifyTokenGivenPublicKey(token, privateKEY) {
        return new Promise((resolve, reject) => {
            jwt.verify(token, privateKEY, (error, payLoad) => {
                if (error)
                    return reject(error);
                resolve(payLoad);
            });
        });
    }

}
