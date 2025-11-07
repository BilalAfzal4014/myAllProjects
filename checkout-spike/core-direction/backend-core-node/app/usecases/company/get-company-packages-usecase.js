const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const ProfileCategoryRepo = require("../../repositories/profileCategoryRepo");
const ErrorTypes = require("../../errors/error-types");
module.exports = class GetCompanyPackagesUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.payload = payload;
    }

    fetchCompanies() {
        return this.getCompanies();
    }

    getCompanies() {
        return FosUserUserRepo.fetchCompanies(this.payload).then((companies) => {
            return this.loopCategories(companies);
        });
    }

    async loopCategories(companies) {
        let companies_with_user_profiles = []
        for (let i = 0; i < companies.length; i++) {
            const user_profiles = await this.fetchUserProfiles(companies[i].id);
            companies_with_user_profiles.push({...companies[i], categories: [...user_profiles]})
        }
        return companies_with_user_profiles;
    }

    async fetchUserProfiles(id) {
        return ProfileCategoryRepo.fetchUserProfiles(id);
    }

}
