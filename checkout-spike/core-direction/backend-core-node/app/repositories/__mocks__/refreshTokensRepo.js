const GeneralHelper = require("../../helpers/general-helper");
const RefreshTokenModel = require("../../../tests/json-data-for-test-cases/models/refreshTokenModel");

module.exports = class RefreshTokensRepo {

    static save(refreshToken, transaction = null) {
        if (!refreshToken.id) {
            refreshToken.id = 0;
        }

        return Promise.resolve(refreshToken);
    }

    static upsert(refreshToken, options, transaction = null) {
        for (let refreshTokenRecord of RefreshTokenModel) {
            if (
                GeneralHelper.matchAllObjectKeyValuesWithOtherObject(options, refreshTokenRecord)
            ) {
                refreshToken.id = refreshTokenRecord.id;
                break;
            }
        }
        return RefreshTokensRepo.save(refreshToken, transaction);
    }

}