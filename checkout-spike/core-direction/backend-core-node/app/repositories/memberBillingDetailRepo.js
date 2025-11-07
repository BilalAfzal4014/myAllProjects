const BaseRepo = require("./baseRepo");
const MemberBillingDetailModel = require("../models/memberBillingDetailModel");

module.exports = class MemberBillingDetailRepo extends BaseRepo {
    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return MemberBillingDetailRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return MemberBillingDetailRepo.findByAttributeWhereIdIsNotAndGivenModel(MemberBillingDetailModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static save(memberBillingDetail) {
        if (!memberBillingDetail.id) {
            return MemberBillingDetailModel.query().insertAndFetch(memberBillingDetail);
        } else {
            return MemberBillingDetailModel.query().updateAndFetchById(
                memberBillingDetail.id,
                memberBillingDetail
            );
        }
    }

}