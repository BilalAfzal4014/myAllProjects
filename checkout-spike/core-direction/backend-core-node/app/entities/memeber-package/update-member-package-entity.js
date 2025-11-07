const updateMemberPackageValidationRules = require("./validation-rules/update-member-package-validation-rules.json");

module.exports = class UpdateMemberPackageEntity {
    getValidationRules = () => (updateMemberPackageValidationRules);

    getUserProvidedFields = () => (["id"]);

    getFieldsForUniqueness = () => ([]);
}