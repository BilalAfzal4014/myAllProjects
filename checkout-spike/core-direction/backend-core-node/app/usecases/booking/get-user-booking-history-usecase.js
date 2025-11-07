const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const GeneralHelper = require('../../helpers/general-helper');

module.exports = class UserBookingHistoryUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.user_id = payload.user_id;
        this.filter_by_month = payload.query.filter_by_month;
    }

    fetchData() {
        return this.fetchUserBookingHistory();
    }

    fetchUserBookingHistory() {
        return MemberScheduleActivityRepo.fetchUserBookingHistory({
            member_id: this.user_id
        }).then((history) => {

            if(!this.filter_by_month){
                return history;
            }
            return GeneralHelper.groupByArrayElements(['month'],history);
        })
    }



}
