const BaseRepo = require("./baseRepo");
const MemberBillingModel = require("../models/memberBillingModel");

module.exports = class MemberBillingRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return MemberBillingRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return MemberBillingRepo.findByAttributeWhereIdIsNotAndGivenModel(MemberBillingModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static save(memberBilling) {
        if (!memberBilling.id) {
            return MemberBillingModel.query().insertAndFetch(memberBilling);
        } else {
            return MemberBillingModel.query().updateAndFetchById(
                memberBilling.id,
                memberBilling
            );
        }
    }
}
