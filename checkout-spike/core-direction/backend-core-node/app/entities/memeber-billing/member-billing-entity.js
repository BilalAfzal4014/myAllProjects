const CreateMemberBillingEntity = require("./create-member-billing-entity");
const UpdateMemberBillingEntity = require("./update-member-billing-entity");
const validationRules = require("./validation-rules/member-billing-validation-rules.json");

module.exports = class MemberBillingEntity {
    constructor(memberBilling) {
        this.memberBillingEntityDesiredInstance = MemberBillingEntity.getDesiredInstance(memberBilling);
    }

    static getDesiredInstance(memberBilling) {
        if (memberBilling.id === undefined) {
            return new CreateMemberBillingEntity();
        }
        return new UpdateMemberBillingEntity();
    }

    getValidationRules = () => ([...validationRules, ...this.memberBillingEntityDesiredInstance.getValidationRules()])

    getUserProvidedFields = () => (["user_id", "charge_id", "track_id", "card_id", "last_four", "vat", "amount", "currency", "transaction_response", "transaction_type", "status", "payment_gateway_used", "failure_code", "product_purchased", ...this.memberBillingEntityDesiredInstance.getUserProvidedFields()]);

    getFieldsForUniqueness = () => ([...this.memberBillingEntityDesiredInstance.getFieldsForUniqueness()]);
}