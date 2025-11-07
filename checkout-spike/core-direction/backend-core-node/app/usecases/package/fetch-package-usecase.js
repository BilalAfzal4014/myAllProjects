const PackageRepo = require("../../repositories/packageRepo");

module.exports = class FetchPackageUseCase{
    static fetchPackageById(packageId){
        return PackageRepo.findPackageById(packageId);
    }

    static fetchPackageByCode(code){
        return PackageRepo.findPackageByCode(code);
    }
}