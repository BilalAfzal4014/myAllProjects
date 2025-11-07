const validationRules = require("./validation-rules/purhcase-package-validation-rules.json");

module.exports = class PurchasePackageEntity {
    getUserProvidedFields = () => (['user_id', 'package_id']);

    getValidationRules = () => validationRules;

    getFieldsForUniqueness = () => [];
}