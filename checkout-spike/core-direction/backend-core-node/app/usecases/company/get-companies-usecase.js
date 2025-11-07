const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const ProfileCategoryRepo = require("../../repositories/profileCategoryRepo");
const ErrorTypes = require("../../errors/error-types");
const redis = require('redis');
const client = redis.createClient();

module.exports = class GetCompaniesUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.payload = payload;
    }
    fetchCompanies() {
        return this.getCompanies()
        // if( (this.payload.keyword &&this.payload.keyword!='') || (this.payload.lat &&this.payload.lat!='') || (this.payload.ln &&this.payload.lng!='')){
        // }else{
        //     return new Promise((resolve, reject) => {
        //         client.get('get_companies_'+this.payload.profile_cat_id, (err, data) => {
        //             if (err) {
        //                 reject(err);
        //                 return;
        //             }
        //             if (data !== null) {
        //                 resolve(JSON.parse(data));
        //             } else {
        //                 resolve(this.getCompanies());
        //             }
        //         })
        //     })
        // }
    }

    getCompanies() {
        return FosUserUserRepo.fetchCompanies(this.payload).then(async (companies) => {
            let looped_companies = await this.loopCategories(companies);
            client.setex('get_companies_'+this.payload.profile_cat_id, 3600, JSON.stringify(looped_companies));
            return looped_companies;
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
