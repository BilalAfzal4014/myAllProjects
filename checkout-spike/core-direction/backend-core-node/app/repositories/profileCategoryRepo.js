const BaseRepo = require("./baseRepo");
const ProfileCategoryModel = require("../models/profileCategoryModel");

module.exports = class ProfileCategoryRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ProfileCategoryRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ProfileCategoryRepo.findByAttributeWhereIdIsNotAndGivenModel(ProfileCategoryModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static findCompanyProfileCategoriesByUserId(id) {
        return ProfileCategoryModel.findCompanyProfileCategoriesByUserId(id);
    }
    static getProfileCategoryFilter() {
        return ProfileCategoryModel.getProfileCategoryFilter();
    }

    static fetchUserProfiles(id) {
        return ProfileCategoryModel.fetchUserProfiles(id);
    }
}
