const ActivitySchedulePackageRepo = require("../../repositories/activitySchedulePackageRepo");

module.exports = class FetchActivitySchedulePackageUseCase {

    static fetchPackageAndItsSpecificActivity(package_id, activity_id) {
        return ActivitySchedulePackageRepo.fetchPackageAndItsSpecificActivity(package_id, activity_id);
    }
}