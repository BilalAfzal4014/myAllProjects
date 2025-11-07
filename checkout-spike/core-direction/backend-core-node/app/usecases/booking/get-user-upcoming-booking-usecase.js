const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const GeneralHelper = require('../../helpers/general-helper');

module.exports = class UserUpComingBookingUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.user_id = payload.user_id;
        this.filter_by_month = payload.query.filter_by_month;
    }

    fetchData() {
        return this.fetchUserBookingUpcoming();
    }

    fetchUserBookingUpcoming() {
        return MemberScheduleActivityRepo.fetchUserUpcomingBooking({
            member_id: this.user_id,
        }).then((classes) => {

            if(!this.filter_by_month){
                return classes;
            }
            return GeneralHelper.groupByArrayElements(['month'],classes);
        })
    }



}
