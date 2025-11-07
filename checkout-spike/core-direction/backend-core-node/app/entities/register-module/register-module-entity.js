const validationRulesForUser = require("./validation-rules-user.json");
const validationRulesForMemberKey = require("./validation-rules-member-key.json");

module.exports = class RegisterModuleEntity {
    getValidationRulesForUser = () => validationRulesForUser;

    getUserProvidedFieldsForUser = () => (['email', 'password','phone_number','first_name','last_name', 'date_of_birth', 'gender', 'country']);

    getFieldsForUniquenessForUser = () => ([]);


    getValidationRulesForMemberKey = () => validationRulesForMemberKey;

    getUserProvidedFieldsForMemberKey = () => (['key_id']);

    getFieldsForUniquenessForMemberKey = () => ([]);


}
