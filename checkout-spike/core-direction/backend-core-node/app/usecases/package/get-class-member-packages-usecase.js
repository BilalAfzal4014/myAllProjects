const BaseUseCase = require("../base/base-usecase");
const MemberPackageRepo = require("../../repositories/memberPackageRepo");
const PriceModification = require("./PriceModification");
module.exports = class GetClassMemberPackageUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
    }

    fetchClassMemberPackages() {
        return this.getMemberPackages();
    }

    getMemberPackages() {
        return MemberPackageRepo.fetchClassMemberPackages({
            member_id: this.payLoad.member_id,
            class_id: this.payLoad.class_id
        }).then((packages) => {
            if (packages.length > 0) {
                return PriceModification.isDiscountExistForUser(packages,this.payLoad.member_id);
            } else {
                return [];
            }
        });
    }

}
