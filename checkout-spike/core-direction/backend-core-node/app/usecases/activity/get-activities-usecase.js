const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const GeneralHelper = require('../../helpers/general-helper');
const ActivityScheduleRepo = require("../../repositories/activityScheduleRepo");

module.exports = class GetActivitiesUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.body = {};
        this.setBody(payload)

    }

    setBody(payload) {
        this.body = {
            member_id: payload.user_id,
            start_date: (payload.start_date) ? payload.start_date : null,
            end_date: (payload.end_date) ? payload.end_date : null,
            keyword: (payload.keyword) ? payload.keyword : null,
            activity_type_ids: (payload.activity_type_ids) ? payload.activity_type_ids : null,
            zone_ids: (payload.zone_ids) ? payload.zone_ids : null,
            profile_cat_id: (payload.profile_cat_id) ? payload.profile_cat_id : null,
            lat: (payload.lat) ? payload.lat : null,
            lng: (payload.lng) ? payload.lng : null,
            is_online: payload.is_online
        }
    }

    fetchActivities() {
        return this.getActivities();
    }

    async getActivities() {
        return MemberScheduleActivityRepo.fetchActivities(this.body).then(async (activities) => {
            return await this.formatActivities(activities);

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

    static fetchActivityScheduleById(activityId) {
        return ActivityScheduleRepo.fetchActivityScheduleAgaintClassId(activityId, true);
    }
}
