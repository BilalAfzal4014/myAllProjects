const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");

module.exports = class FetchMemberScheduleActivityUseCase {

    static fetchSpecificMSAOfAMemberByMemberAndScheduleDetailId(member_id, schedule_detail_id, transaction = null) {
        return MemberScheduleActivityRepo.findByMemberAndScheduleDetailId(member_id, schedule_detail_id, transaction);
    }

    static fetchSpecificBookedOrReservedMSAOfAMemberByMemberAndScheduleDetailId(member_id, schedule_detail_id, transaction = null) {
        return MemberScheduleActivityRepo.findBookedOrReservedByMemberAndScheduleDetailId(member_id, schedule_detail_id, transaction);
    }

    static fetchSpecificMSAOfAMemberByMemberAndScheduleDetailAndMemberPackageId(member_id, schedule_detail_id, member_package_id) {
        return MemberScheduleActivityRepo.findByMemberAndScheduleDetailAndMemberPackageId(member_id, schedule_detail_id, member_package_id);
    }

    static acquireLockOnTable(transaction = null) {
        return MemberScheduleActivityRepo.acquireLockOnTable(transaction);
    }

    static unLockTable(transaction = null) {
        return MemberScheduleActivityRepo.unLockTable(transaction);
    }

    static checkIfSlotLeft(schedule_id, includingUsers = null, transaction = null) {
        return MemberScheduleActivityRepo.fetchAvailableSlots(schedule_id, includingUsers, transaction);
    }

    static fetchAvailableSlots(schedule_id, includingUsers = null,transaction = null){
        return MemberScheduleActivityRepo.fetchAvailableSlotsV1(schedule_id, includingUsers, transaction);
    }
}
