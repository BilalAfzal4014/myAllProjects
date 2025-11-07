const validationRules = require("./validation-rules-filter");

module.exports = class FilterEntity {
    getValidationRules = () => validationRules;
    
    getUserProvidedFields = () => (['type']);

    getFieldsForUniqueness = () => ([]);
}
