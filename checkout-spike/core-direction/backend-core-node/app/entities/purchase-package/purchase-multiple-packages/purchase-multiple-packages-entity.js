const validationRules = require("./validation-rules/purhcase-multiple-packages-validation-rules.json");

module.exports = class PurchaseMultiplePackagesEntity {
    getUserProvidedFields = () => (["user_id", "packages"]);

    getValidationRules = () => validationRules;

    getFieldsForUniqueness = () => [];
}