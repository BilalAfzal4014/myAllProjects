const updateMemberScheduleActivityValidationRules = require("./validation-rules/update-member-schedule-activity-validation-rules.json");

module.exports = class UpdateMemberScheduleActivityEntity {
    getValidationRules = () => (updateMemberScheduleActivityValidationRules);

    getUserProvidedFields = () => (["id"]);

    getFieldsForUniqueness = () => ([]);
}