const validationRules = require("./validation-rules.json");

module.exports = class LoginWithCredentialsEntity {
    getValidationRules = () => validationRules;

    getUserProvidedFields = () => (['email', 'password']);

    getFieldsForUniqueness = () => ([]);
}