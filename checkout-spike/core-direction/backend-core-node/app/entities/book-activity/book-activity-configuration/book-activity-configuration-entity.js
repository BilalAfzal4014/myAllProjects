const validationRules = require("./validation-rules/book-activity-configuration-validation-rules.json");

module.exports = class BookActivityConfigurationEntity {

    getValidationRules = () => (validationRules)

    getUserProvidedFields = () => (["user_id", "modified_by", "package_id", "member_package_id", "schedule_detail_id", "type"]);

    getFieldsForUniqueness = () => ([]);
}