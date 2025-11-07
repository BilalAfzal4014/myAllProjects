const BaseRepo = require("./baseRepo");
const PackageModel = require("../models/packageModel");

module.exports = class PackageRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return PackageRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return PackageRepo.findByAttributeWhereIdIsNotAndGivenModel(PackageModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static findByFacilityId(facility_id) {
        return PackageRepo.findByAttributes([], [{
            key: "facility_id",
            value: facility_id
        }], true);
    }

    static findPackagesWithFacilityID(facility_id) {
        return PackageModel.findPackagesWithFacilityID(facility_id);
    }

    static findPackagesWithCompanyID(facility_id) {
        return PackageModel.findPackagesWithCompanyID(facility_id);
    }

    static findDefaultPackage() {
        return PackageRepo.findByAttributes([], [{
            key: "code",
            value: 'DEFAULT_PACKAGE'
        }], true).first();
    }

    static findPackageById(package_id) {
        return PackageRepo.findByAttributes([], [{
            key: "id",
            value: package_id
        }], true).first();
    }

    static findPackageByCode(code) {
        return PackageRepo.findByAttributes([], [{
            key: "code",
            value: code
        }], true).first();
    }

    static findPackagesDetail(package_ids) {
        return PackageModel.findPackagesDetail(package_ids);
    }
}
