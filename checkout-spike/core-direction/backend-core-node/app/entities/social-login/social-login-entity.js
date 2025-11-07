const validationRules = require("./validation-rules.json");

module.exports = class LoginWithCredentialsEntity {
    getValidationRules = () => validationRules;

    getUserProvidedFields = () => (['first_name','email', 'last_name','id','type','need_to_verify_email']);

    getFieldsForUniqueness = () => ([]);
}