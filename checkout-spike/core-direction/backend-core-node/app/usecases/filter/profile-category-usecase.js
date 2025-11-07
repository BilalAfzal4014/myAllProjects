const _ = require("lodash");
const {v4: uuid} = require("uuid");
const BaseUseCase = require("../base/base-usecase");
const ProfileCategoryRepo = require("../../repositories/profileCategoryRepo");
const FilterModuleConstants = require("../../constants/filter-module");

module.exports = class ProfileCategoryUseCase extends BaseUseCase {

    constructor() {
        super();
    }

    getData() {
        return ProfileCategoryRepo.getProfileCategoryFilter();
    }

}
