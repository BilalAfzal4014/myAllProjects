const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");

module.exports = class CheckInActivityUseCase extends BaseUseCase {

    constructor(req) {
        super();
        this.user = req.user_id
        this.member_schedule_activity_id = req.body.member_schedule_activity_id

    }

    cancelActivity(){

        return MemberScheduleActivityRepo.checkInActivity({
            member_id: this.user,
            slot_id: this.member_schedule_activity_id
        });
    }
}