const BaseRepo = require("./baseRepo");
const ActivitySchedulePackageModel = require("../models/activitySchedulePackageModel");

module.exports = class ActivitySchedulePackageRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ActivitySchedulePackageRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ActivitySchedulePackageRepo.findByAttributeWhereIdIsNotAndGivenModel(ActivitySchedulePackageModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchUserPackage(package_id) {
        return ActivitySchedulePackageModel.fetchUserPackage(package_id)
    }

    static fetchPackageAndItsSpecificActivity(package_id, activity_id) {
        return ActivitySchedulePackageRepo.findByAttributes([], [{
            key: "package_id",
            value: package_id
        }, {
            key: "activityschedule_id",
            value: activity_id
        }], false).first();
    }

}
