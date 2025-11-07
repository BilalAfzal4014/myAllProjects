const validationRules = require("./validation-rules/multiple-entity-validation-rules.json");

module.exports = class MultipleActivityEntity {

    getValidationRules = () => (validationRules);

    getUserProvidedFields = () => (["user_id", "schedule_detail_id", "users"]);

    getFieldsForUniqueness = () => ([]);
}