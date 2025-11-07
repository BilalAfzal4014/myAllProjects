const updateMemberBillingDetailValidationRules = require("./validation-rules/update-member-billing-detail-validation-rules.json");

module.exports = class UpdateMemberBillingDetailEntity {
    getValidationRules = () => (updateMemberBillingDetailValidationRules);

    getUserProvidedFields = () => (["id"]);

    getFieldsForUniqueness = () => ([]);
}