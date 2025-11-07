const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const PackageRepo = require("../../repositories/packageRepo");
const ActivityRepo = require("../../repositories/activityRepo");
const ErrorTypes = require("../../errors/error-types");
const GeneralHelper = require('../../helpers/general-helper');
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");

module.exports = class CompanyActivitiesUseCase extends BaseUseCase {
    constructor(body) {
        super();
        this.body = body;
    }

    fetchData() {
        return ActivityRepo.fetchCompanyActivities({
            ...this.body,
            facility_id: this.body.id,
            member_id: this.body.user_id
        }).then((activities) => {
            return this.formatActivities(activities);
        });
    }


    formatActivities = async (activities) => {

        activities = await this.getBookedSlots(activities);

        return GeneralHelper.groupByArrayElements(['startDate'], activities);
    }

    async getBookedSlots(activities) {

        let formattedArray = [];
        for (const activity of activities) {

            await MemberScheduleActivityRepo.getBookedSlots(activity.schedule_detail_id).then((count) => {

                activity.booked_slots = count;
                activity.disable_booking = false;
                if (count >= activity.slots) {
                    activity.booked_slots = activity.slots;
                    activity.disable_booking = true;
                }
                formattedArray.push(activity);
            })
        }

        return formattedArray;
    }
}
