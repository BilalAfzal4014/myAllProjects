const BaseRepo = require("./baseRepo");
const RefreshTokenModel = require("../models/refreshTokensModel");
module.exports = class RefreshTokensRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return RefreshTokensRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return RefreshTokensRepo.findByAttributeWhereIdIsNotAndGivenModel(RefreshTokenModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static save(refreshToken, transaction = null) {
        if (!refreshToken.id) {
            return RefreshTokenModel.query(transaction).insertAndFetch(refreshToken);
        } else {
            return RefreshTokenModel.query(transaction).updateAndFetchById(refreshToken.id, refreshToken);
        }
    }

    static upsert(refreshToken, options, transaction = null) {
        return RefreshTokenModel.query(transaction)
            .where(options)
            .then(([result]) => {
                if (result) {
                    refreshToken.id = result.id;
                }
                return RefreshTokensRepo.save(refreshToken, transaction);
            });
    }
}