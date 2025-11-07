const validationRules = require("./social-login-validation-rules.json");

module.exports = class SocialLoginEntity {

    getUserProvidedFields = () => {

        let userProvidedFields = ['access_token', 'type', 'client'];

        return userProvidedFields;
    };

    getFieldsForUniqueness = () => ([]);

    getValidationRules = () => {
        return validationRules;
    };

};