const BaseRepo = require("./baseRepo");
const FosUserUserModel = require("../../../tests/json-data-for-test-cases/models/fosUserUserModel");

module.exports = class FosUserUserRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return FosUserUserRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return FosUserUserRepo.findByAttributeWhereIdIsNotAndGivenModel(FosUserUserModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static updateAttributes(conditionalAttributes, attributes, transaction = null) {
        return Promise.resolve(true);
    }

    static updateToken(userID, token, transaction = null) {
        return FosUserUserRepo.updateAttributes([{
            clause: "where",
            key: "id",
            value: userID
        }], {token}, transaction);
    }

    static updateMultipleFieldsByEmail(email, updatePayLoad, transaction = null) {
        return FosUserUserRepo.updateAttributes([{
            clause: "where",
            key: "email",
            value: email
        }], updatePayLoad, transaction);
    }

    static updatePasswordByEmail(email, password, transaction = null) {
        return FosUserUserRepo.updateAttributes([{
            clause: "where",
            key: "email",
            value: email
        }], {password: password}, transaction);
    }

    static findByConfirmationToken(token) {
        return FosUserUserRepo.findByAttributes([], [{
            key: "confirmation_token",
            value: token
        }], true);
    }

}