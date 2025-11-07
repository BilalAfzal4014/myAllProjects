const CreateMemberBillingDetailEntity = require("./create-member-billing-detail-entity");
const UpdateMemberBillingDetailEntity = require("./update-member-billing-detail-entity");
const validationRules = require("./validation-rules/member-billing-detail-validation-rules.json");

module.exports = class MemberBillingDetailEntity {
    constructor(memberBillingDetail) {
        this.memberBillingDetailEntityDesiredInstance = MemberBillingDetailEntity.getDesiredInstance(memberBillingDetail);
    }

    static getDesiredInstance(memberBillingDetail) {
        if (memberBillingDetail.id === undefined) {
            return new CreateMemberBillingDetailEntity();
        }
        return new UpdateMemberBillingDetailEntity();
    }

    getValidationRules = () => ([...validationRules, ...this.memberBillingDetailEntityDesiredInstance.getValidationRules()])

    getUserProvidedFields = () => (["member_billing_id", "amount", "member_package_id", ...this.memberBillingDetailEntityDesiredInstance.getUserProvidedFields()]);

    getFieldsForUniqueness = () => ([...this.memberBillingDetailEntityDesiredInstance.getFieldsForUniqueness()]);
}