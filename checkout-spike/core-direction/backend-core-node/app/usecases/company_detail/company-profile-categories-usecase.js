const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const ProfileCategoryRepo = require("../../repositories/profileCategoryRepo");
const ErrorTypes = require("../../errors/error-types");
module.exports = class CompanyProfileCategoriesUseCase extends BaseUseCase {
    constructor(id) {
        super();
        this.id = id;
    }

    fetchData() {
        return this.fetchCompanyProfileCategories()
    }

    fetchCompanyProfileCategories() {
        return ProfileCategoryRepo.findCompanyProfileCategoriesByUserId(this.id).then((categories) => {
            return categories;
        });
    }
}
