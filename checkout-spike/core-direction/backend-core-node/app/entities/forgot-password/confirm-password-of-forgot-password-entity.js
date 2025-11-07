const validationRules = require("./validation-rules-confirm-password-of-forgot-password-entity.json");

module.exports = class ConfirmPasswordOfForgotPasswordEntity {

    getValidationRules = () => validationRules;

    getUserProvidedFields = () => (['password']);

    getFieldsForUniqueness = () => ([]);

}