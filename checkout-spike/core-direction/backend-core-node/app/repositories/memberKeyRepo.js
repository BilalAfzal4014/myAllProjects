const BaseRepo = require("./baseRepo");
const MemberKeyModel = require("../models/memberKeyModel");

module.exports = class MemberKeyRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return MemberKeyRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return MemberKeyRepo.findByAttributeWhereIdIsNotAndGivenModel(MemberKeyModel, attributes, id, extraAttributes, false);
    }

    static save(memberKeyData, transaction = null) {
        if (!memberKeyData.id) {
            return MemberKeyModel.query(transaction).insertAndFetch(memberKeyData);
        } else {
            return MemberKeyModel.query(transaction).updateAndFetchById(memberKeyData.id, memberKeyData);
        }
    }
    static isKeyRedeemed(payload) {
        return MemberKeyModel.isKeyRedeemed(payload);
    }
    static addRecord(payload) {
        return MemberKeyModel.addRecord(payload);
    }
    static isDiscountExistForUser(member_id) {
        return MemberKeyModel.isDiscountExistForUser(member_id);
    }
    static countConsumed(member_package_id,member_id) {
        return MemberKeyModel.countConsumed(member_package_id,member_id);
    }

}
