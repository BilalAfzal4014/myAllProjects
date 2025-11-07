const validationRules = require("./validation-rules-refresh-token");

module.exports = class RefreshTokenEntity {
    getValidationRules = () => validationRules;
    
    getUserProvidedFields = () => (['refresh_token']);

    getFieldsForUniqueness = () => ([]);
}
