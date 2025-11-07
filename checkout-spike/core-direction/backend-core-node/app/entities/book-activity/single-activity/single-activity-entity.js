const validationRules = require("./validation-rules/single-entity-validation-rules.json");

module.exports = class SingleActivityEntity {

    getValidationRules = () => (validationRules);

    getUserProvidedFields = () => (["user_id", "first_name", "last_name", "email", "package_id", "member_package_id", "schedule_detail_id", "modified_by", "type"]);

    getFieldsForUniqueness = () => ([]);
}