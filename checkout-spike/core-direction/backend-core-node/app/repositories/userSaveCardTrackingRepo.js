const BaseRepo = require("./baseRepo");
const UserSaveCardTrackingModel = require("../models/userSaveCardTrackingModel");
const FosUserUserModel = require("../models/fosUserUserModel");

module.exports = class UserSaveCardTrackingRepo extends BaseRepo {
    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return UserSaveCardTrackingRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return UserSaveCardTrackingRepo.findByAttributeWhereIdIsNotAndGivenModel(UserSaveCardTrackingModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static save(saveCardDetails) {
        if (!saveCardDetails.id) {
            return UserSaveCardTrackingModel.query().insertAndFetch(saveCardDetails);
        } else {
            return UserSaveCardTrackingModel.query().updateAndFetchById(
                saveCardDetails.id,
                saveCardDetails
            );
        }
    }

    static fetchCardOfSpecificUserById(id, member_id) {
        return UserSaveCardTrackingRepo.findByAttributes([], [{
            key: "id",
            value: id
        }, {
            key: "member_id",
            value: member_id
        }], true)
            .then(([userSavedCard]) => userSavedCard);
    }

    static fetchAllCardsOfAUser(member_id) {
        return UserSaveCardTrackingRepo.findByAttributes([], [{
            key: "member_id",
            value: member_id
        }], true);
    }

    static updateAttributes(conditionalAttributes, attributes, transaction = null) {
        const query = UserSaveCardTrackingModel.query(transaction).update(attributes)
        for (let attribute of conditionalAttributes) {
            query[attribute.clause](attribute.key, attribute.value);
        }
        return query;
    }

    static deleteCardOfAUser(id, member_id, softDelete = true, transaction = null) {
        if (softDelete) {
            return UserSaveCardTrackingRepo.softDeleteCardOfAUser(id, member_id, transaction);
        }
        return UserSaveCardTrackingModel.query(transaction).delete()
            .where("id", id)
            .where("member_id", member_id);
    }

    static softDeleteCardOfAUser(id, member_id, transaction = null) {
        return UserSaveCardTrackingRepo.updateAttributes([{
            clause: "where",
            key: "id",
            value: id
        }, {
            clause: "where",
            key: "member_id",
            value: member_id
        }], {is_deleted: 1}, transaction);
    }
}