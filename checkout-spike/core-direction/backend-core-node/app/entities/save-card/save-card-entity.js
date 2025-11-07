const CreateSaveCardEntity = require("./create-save-card-entity");
const UpdateSaveCardEntity = require("./update-save-card-entity");
const validationRules = require("./validation-rules/save-card-validation-rules.json");

module.exports = class SaveCardEntity {
    constructor(saveCardDetails) {
        this.saveCardEntityDesiredInstance = SaveCardEntity.getDesiredInstance(saveCardDetails);
    }

    static getDesiredInstance(saveCardDetails) {
        if (saveCardDetails.id === undefined) {
            return new CreateSaveCardEntity();
        }
        return new UpdateSaveCardEntity();
    }

    getValidationRules = () => ([...validationRules, ...this.saveCardEntityDesiredInstance.getValidationRules()])

    getUserProvidedFields = () => (["save_card_tracking_id", "member_id", "last_four", "payment_gateway_used", "card_holder_name", "card_type", "modifiedby", "is_deleted", ...this.saveCardEntityDesiredInstance.getUserProvidedFields()]);

    getFieldsForUniqueness = () => ([...this.saveCardEntityDesiredInstance.getFieldsForUniqueness()]);
}