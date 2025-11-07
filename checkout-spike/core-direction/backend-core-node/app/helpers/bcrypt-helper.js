const bcrypt = require('bcrypt');

module.exports = class BcryptHelper {
    static saltRounds = 10;

    static compareFirstBcryptStrWithSecondNormalStr(bcryptStr,normalStr,replaceStrInBcryptForSymfony = true) {

        return bcrypt.compare(normalStr, replaceStrInBcryptForSymfony ? BcryptHelper.replaceStringForSymfony(bcryptStr) : bcryptStr);
    }

    /**
     * replace character for symfony password hash
     */
    static replaceStringForSymfony(hashPassword){

        return hashPassword.replace('$2y$', '$2b$');
    }
    static convertStrIntoBcryptStr(str, rounds = null) {
        return new Promise((resolve, reject) => {
            bcrypt.hash(str, rounds ? rounds : BcryptHelper.saltRounds, function (error, hash) {
                if (error)
                    return reject(error);
                
                resolve(hash)
            });
        });
    }
}