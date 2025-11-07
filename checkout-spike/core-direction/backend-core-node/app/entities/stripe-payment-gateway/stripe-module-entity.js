const validationRulesForSaveCard = require("./validation-rules-save-card.json");
const validationRulesPaymentThroughSaveCard = require('./validation-rules-payment-through-save-card.json');
module.exports = class StripeModuleEntity {
    getValidationRulesForSaveStripeCards = () => validationRulesForSaveCard;

    getFieldsForUniquenessForSaveStripeCards = () => ([]);

    getStripeCardProvidedFields = () => (['id','customer','client_secret']);

    getValidationRulesPaymentThroughSaveCard = () => validationRulesPaymentThroughSaveCard;

    getPaymentThroughSaveCardFields = () => (['customer','payment_method','package_id']);
}