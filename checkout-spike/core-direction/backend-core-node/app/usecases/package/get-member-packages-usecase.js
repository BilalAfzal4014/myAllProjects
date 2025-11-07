const BaseUseCase = require("../base/base-usecase");
const MemberPackageRepo = require("../../repositories/memberPackageRepo");
const PriceModification = require("./PriceModification");

module.exports = class FetchMemberPackageUseCase extends BaseUseCase {

    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
    }

    fetchMemberPackages() {
        return this.getMemberPackages();
    }

    getMemberPackages() {
        return MemberPackageRepo.fetchMemberPackages(this.payLoad).then((packages) => {
            return PriceModification.isDiscountExistForUser(packages, this.payLoad.user_id);
        });
    }

    static fetchSpecificActivePackageOfAMember(package_id, user_id) {
        return MemberPackageRepo.fetchSpecificPackageOfAMember(package_id, user_id)
            .then((packages) => {
                return packages;
            });
    }

    static fetchSpecificActivePackageOfAMemberByIdWithWriteLock(id, package_id, user_id, transaction) {
        return MemberPackageRepo.fetchSpecificPackageOfAMemberByIdWithWriteLock(id, package_id, user_id, transaction)
            .then((packages) => {
                return packages;
            });
    }

    static fetchMemberPackagesByIdsAndModifiedBy(ids, modifiedBy, status){
        return MemberPackageRepo.fetchMemberPackagesByIdsAndModifiedBy(ids, modifiedBy, status)
            .then((packages) => {
                return packages;
            });
    }
}
