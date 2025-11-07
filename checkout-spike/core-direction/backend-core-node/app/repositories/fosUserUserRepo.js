const BaseRepo = require("./baseRepo");
const FosUserUserModel = require("../models/fosUserUserModel");

module.exports = class FosUserUserRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return FosUserUserRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return FosUserUserRepo.findByAttributeWhereIdIsNotAndGivenModel(FosUserUserModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static updateAttributes(conditionalAttributes, attributes, transaction = null) {
        const query = FosUserUserModel.query(transaction).update(attributes)
        for (let attribute of conditionalAttributes) {
            query[attribute.clause](attribute.key, attribute.value);
        }
        return query;
    }

    static save(userData, transaction = null) {
        if (!userData.id) {
            return FosUserUserModel.query(transaction).insertAndFetch(userData);
        } else {
            return FosUserUserModel.query(transaction).updateAndFetchById(userData.id, userData);
        }
    }

    static updateToken(userID, token, transaction = null) {
        return FosUserUserRepo.updateAttributes([{
            clause: "where",
            key: "id",
            value: userID
        }], {token}, transaction);
    }

    static findById = (id) => {
        return FosUserUserRepo.findByAttributes([], [{
            key: "id",
            value: id
        }], true).first();
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


    static findBySocialId(socialId, socialColumn) {

        return FosUserUserRepo.findByAttributes([], [{
            key: socialColumn,
            value: socialId
        }], true).first();
    }

    static findByToken(token) {
        return FosUserUserRepo.findByAttributes([], [{
            key: 'token',
            value: token
        }], true).first();
    }

    static findByEmail(email) {
        return FosUserUserRepo.findByAttributes([], [{
            key: 'email',
            value: email
        }], false).first();
    }

    static updateMultipleFieldsById(id, updatePayLoad, transaction = null) {
        return FosUserUserRepo.updateAttributes([{
            clause: "where",
            key: "id",
            value: id
        }], updatePayLoad, transaction);
    }

    static fetchCompanies(payload) {
        return FosUserUserModel.fetchCompanies(payload);
    }
    static getFacilityCompanies() {
        return FosUserUserModel.getFacilityCompanies();
    }

}
