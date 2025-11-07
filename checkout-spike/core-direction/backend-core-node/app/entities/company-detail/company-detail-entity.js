const validationRules = require("./validation-rules-filter");

module.exports = class CompanyDetailEntity {
    getValidationRules = () => validationRules;

    getUserProvidedFields = () => (['type', 'id']);

    getFieldsForUniqueness = () => ([]);

    getUserFieldsForBiography = () => (['about_corporate']);

    getUserFieldsForBasicInfo = () => (['company_banner','latitude','company_logo','website','company_name','phone','longitude','username','email','phone','address']);
}
