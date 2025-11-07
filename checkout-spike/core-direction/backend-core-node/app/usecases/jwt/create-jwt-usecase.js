const jwt = require('jsonwebtoken');
const FileHelpers = require("../../helpers/file-helpers");

module.exports = class CreatJWTUseCase {

    static createJwt(payLoad) {
        return CreatJWTUseCase.readPrivateKey()
            .then((privateKEY) => {
                return CreatJWTUseCase.jwtTokenAssignment(payLoad, privateKEY)
            }).then((token) => {
                return token;
            });
    }

    static readPrivateKey() {
        return FileHelpers.readFileFromGivenPath(process.env.private_key_path_for_jwt);
    }

    static jwtTokenAssignment(payLoad, privateKEY) {
        return new Promise((resolve, reject) => {
            jwt.sign(payLoad, privateKEY, {expiresIn: 30 * day()}, function (error, token) {
                if (error)
                    return reject(error);

                resolve(token)
            });
        });
    }

}

const day = () => {
    return secondsInADay();
}

const secondsInADay = () => {
    return 24 * 60 * 60;
}