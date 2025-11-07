const validationRules = require("./validation-rules/user-emergency-validation-rules.json");

module.exports = class UserEmergencyEntity {
    getUserProvidedFields = () => (['firstname', 'lastname', 'phoneNumber', 'email', 'user_id', 'modifiedby']);

    getValidationRules = () => validationRules;

    getFieldsForUniqueness = () => [];
}