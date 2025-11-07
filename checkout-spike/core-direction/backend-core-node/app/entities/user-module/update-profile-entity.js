const validationRules = require("./validation-rules/update-profile-validation-rules.json");

module.exports = class UpdateProfileEntity {
    getUserFieldsForUpdateProfile = () => (['id', 'user_id', 'firstname', 'lastname', 'phone', 'date_of_birth', 'country', 'gender', 'email', 'user_emergency','is_vaccinated']);

    getValidationRules = () => validationRules;

    getFieldsForUniqueness = () => [];
}
