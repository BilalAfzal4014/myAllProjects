const updateMemberBillingValidationRules = require("./validation-rules/update-member-billing-validation-rules.json");

module.exports = class UpdateMemberBillingEntity {
    getValidationRules = () => (updateMemberBillingValidationRules);

    getUserProvidedFields = () => (["id"]);

    getFieldsForUniqueness = () => ([]);
}