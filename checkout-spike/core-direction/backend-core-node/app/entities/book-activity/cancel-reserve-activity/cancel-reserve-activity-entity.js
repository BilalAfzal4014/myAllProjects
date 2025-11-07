const validationRules = require("./validation-rules/cancel-reserve-activity-validation-rules.json");

module.exports = class CancelReserveActivityEntity {

    getValidationRules = () => (validationRules)

    getUserProvidedFields = () => (["user_id", "member_ids"]);

    getFieldsForUniqueness = () => ([]);
}