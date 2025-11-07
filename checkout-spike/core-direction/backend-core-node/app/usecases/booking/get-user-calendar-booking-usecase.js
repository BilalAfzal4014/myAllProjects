const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const GeneralHelper = require("../../helpers/general-helper");

module.exports = class UserCalendarBookingUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.user_id = payload.user_id;
    }

    fetchData() {
        return this.fetchUserBookingCalendar();
    }

    fetchUserBookingCalendar() {
        return MemberScheduleActivityRepo.fetchUserBookingCalendar({
            member_id: this.user_id
        }).then((date) => {

            return GeneralHelper.groupByArrayElements(['class_date'], date);
        })
    }



}
