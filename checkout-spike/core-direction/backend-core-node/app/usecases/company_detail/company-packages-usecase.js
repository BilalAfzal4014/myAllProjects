const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const PackageRepo = require("../../repositories/packageRepo");
const corporateKeyRepo = require("../../repositories/corporateKeyRepo");
const ErrorTypes = require("../../errors/error-types");
const PriceModification = require("../package/PriceModification");
module.exports = class CompanyPackagesUseCase extends BaseUseCase {
    constructor(body) {
        super();
        this.id = body.id;
        this.member_id = body.user_id;
    }

    fetchData() {
        return PackageRepo.findPackagesWithCompanyID(this.id).then((packages) => {
            if (packages.length > 0) {
                return PriceModification.isDiscountExistForUser(packages, this.member_id);
            } else
                return [];
        });
    }
}
