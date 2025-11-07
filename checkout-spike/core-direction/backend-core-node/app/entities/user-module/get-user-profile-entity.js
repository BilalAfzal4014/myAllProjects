module.exports = class UserProfileEntity {
    getUserFieldsForProfile = () => (['firstname','qr_code', 'lastname', 'date_of_birth', 'email','country', 'gender', 'phone', 'company_logo','company_banner','is_vaccinated']);
}
