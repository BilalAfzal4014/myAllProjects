const validationRules = require("./validation-rules.json");

module.exports = class LoginModuleEntity {
    getValidationRules = () => validationRules;

    getUserProvidedFields = () => (['type']);

    getFieldsForUniqueness = () => ([]);
}