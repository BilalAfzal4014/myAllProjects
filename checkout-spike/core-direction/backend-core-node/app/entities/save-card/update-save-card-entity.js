const updateSaveCardValidationRules = require("./validation-rules/update-save-card-validation-rules.json");

module.exports = class UpdateSaveCardEntity {
    getValidationRules = () => (updateSaveCardValidationRules);

    getUserProvidedFields = () => (["id"]);

    getFieldsForUniqueness = () => ([]);
}