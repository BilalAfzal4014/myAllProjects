const validationRules = require("./validation-rules-forgot-password.json");

module.exports = class ForgotPasswordEntity {
    getValidationRules = () => validationRules;
    
    getUserProvidedFields = () => (['email']);

    getFieldsForUniqueness = () => ([]);
}
