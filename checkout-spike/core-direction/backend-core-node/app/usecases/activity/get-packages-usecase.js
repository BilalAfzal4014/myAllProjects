const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const ErrorTypes = require("../../errors/error-types");
const ActivityModuleConstants = require("../../constants/activity-module");
const GeneralHelper = require('../../helpers/general-helper');
const PriceModification = require("../../usecases/package/PriceModification");
module.exports = class GetPackagesUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.class_id = payload.id;
        this.user_id = payload.user_id;

    }

    fetchPackages() {
        return this.getPackages();
    }

    async getPackages() {
        return MemberScheduleActivityRepo.fetchPackages(this.class_id).then( (packages) => {
           return PriceModification.isDiscountExistForUser(packages,this.user_id);

        });
    }


}
