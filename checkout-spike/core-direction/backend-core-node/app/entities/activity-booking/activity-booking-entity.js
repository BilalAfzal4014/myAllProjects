const validationRulesForClassId = require("./validation-rules-for-class-id");
const validationRulesForSlotId = require("./validation-rules-for-slot-id");

module.exports = class CompanyDetailEntity {
    getValidationRulesForClassId = () => validationRulesForClassId;
    getValidationRulesForSlotId = () => validationRulesForSlotId;

    getFieldsForUniqueness = () => ([]);
}
