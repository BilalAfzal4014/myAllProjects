const MemberScheduleActivityRepo = require('../../repositories/memberScheduleActivityRepo')
const PriceModification = require("./PriceModification");
const BaseUseCase = require("../base/base-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class getPackage extends BaseUseCase {

    constructor(packageData) {
        super();
        this.id = packageData.id;
        this.member_id = packageData.member_id
    }

    getPackageDetail = () => {
        return this.getPackage();
    }


    getPackage() {
        return MemberScheduleActivityRepo.fetchPackages(this.id, true).then((packages) => {
            if (packages.length > 0) {

                return PriceModification.isDiscountExistForUser(packages, this.member_id).then((packageData) => {
                    return Promise.resolve(packageData[0])
                })
            } else {
                return [];
            }
        });
    }
}
