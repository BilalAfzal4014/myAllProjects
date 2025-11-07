const BaseRepo = require("./baseRepo");
const MemberScheduleActivityModel = require("../models/memberScheduleActivityModel");
const RefreshTokenModel = require("../models/refreshTokensModel");

module.exports = class MemberScheduleActivityRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false, transaction = null) {
        return MemberScheduleActivityRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted, transaction);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false, transaction = null) {
        return MemberScheduleActivityRepo.findByAttributeWhereIdIsNotAndGivenModel(MemberScheduleActivityModel, attributes, id, extraAttributes, dontFetchDeleted, transaction);
    }

    static fetchActivities(body) {
        return MemberScheduleActivityModel.fetchActivities(body);
    }

    static fetchPackages(class_id, packageDetails = false) {
        return MemberScheduleActivityModel.fetchPackages(class_id, packageDetails);
    }

    static getBookedSlots(id) {
        return MemberScheduleActivityModel.getBookedSlots(id);
    }

    static fetchUserBookingHistory(body) {
        return MemberScheduleActivityModel.fetchUserBookingHistory(body);
    }

    static checkIsActivityExistsAndBooked(body) {
        return MemberScheduleActivityModel.checkIsActivityExistsAndBooked(body);
    }

    static cancelBookedActivity(body) {
        return MemberScheduleActivityModel.cancelBookedActivity(body);
    }

    static checkInActivity(body) {
        return MemberScheduleActivityModel.checkInActivity(body);
    }

    static save(bookingData, transaction = null) {
        if (!bookingData.id) {
            return MemberScheduleActivityModel.query(transaction).insertAndFetch(bookingData);
        } else {
            return MemberScheduleActivityModel.query(transaction).updateAndFetchById(bookingData.id, bookingData);
        }
    }

    static fetchUserUpcomingBooking(body) {
        return MemberScheduleActivityModel.fetchUserUpcomingBooking(body);
    }

    static fetchUserBookingCalendar(body) {
        return MemberScheduleActivityModel.fetchUserCalendarBooking(body);
    }

    static fetchAvailableSlots(slot_id, includingUsers = null, transaction = null) {
        return Promise.all([
            MemberScheduleActivityModel.fetchUsedSlots(slot_id, includingUsers, transaction),
            MemberScheduleActivityModel.fetchTotalSlots(slot_id)
        ]).then(([used, total]) => {
            return used[0].used_slots < total[0].total_slots;
        });

    }

    static fetchAvailableSlotsV1(slot_id, includingUsers = null, transaction = null) {
        return Promise.all([
            MemberScheduleActivityModel.fetchUsedSlots(slot_id, includingUsers, transaction),
            MemberScheduleActivityModel.fetchTotalSlots(slot_id)
        ]).then(([[used], [total]]) => {
            return total.total_slots - used.used_slots;
        });

    }

    static checkIfUserAlreadyHasBooking(member_id, slot_id) {

        return MemberScheduleActivityModel.checkIfUserAlreadyHasBooking(member_id, slot_id);

    }


    static fetchEmailData(slot_id) {
        return MemberScheduleActivityModel.fetchEmailData(slot_id);
    }

    static saveQRImage(id, qrImage) {
        return MemberScheduleActivityModel.saveQR(id, qrImage);
    }

    static findByMemberAndScheduleDetailId(member_id, schedule_detail_id, transaction = null) {
        return MemberScheduleActivityRepo.findByAttributes([], [{
            key: "member_id",
            value: member_id,
        }, {
            key: "schedule_detail_id",
            value: schedule_detail_id
        }], true, transaction);
    }

    static findByMemberAndScheduleDetailAndMemberPackageId(member_id, schedule_detail_id, member_package_id) {
        return MemberScheduleActivityRepo.findByAttributes([], [{
            key: "member_id",
            value: member_id,
        }, {
            key: "schedule_detail_id",
            value: schedule_detail_id
        }, {
            key: "member_package_id",
            value: member_package_id
        }], true).first();
    }

    static acquireLockOnTable(transaction = null) {
        return MemberScheduleActivityModel.acquireLockOnTable(transaction);
    }

    static unLockTable(transaction = null) {
        return MemberScheduleActivityModel.unLockTable(transaction);
    }

    static findBookedOrReservedByMemberAndScheduleDetailId(member_id, schedule_detail_id, transaction = null) {
        return MemberScheduleActivityRepo.findByAttributes([{
            key: "STATUS",
            value: "booked",
        }, {
            key: "STATUS",
            value: "reserved",
        }], [{
            key: "member_id",
            value: member_id,
        }, {
            key: "schedule_detail_id",
            value: schedule_detail_id
        }], true, transaction);
    }

    static upsert(bookingData, options, transaction = null) {
        return MemberScheduleActivityModel.query(transaction)
            .where({
                member_id: bookingData.member_id,
                schedule_detail_id: bookingData.schedule_detail_id,
                package_id: bookingData.package_id,
                STATUS: bookingData.STATUS
            }).then(([result]) => {
                if (result) {
                    bookingData.id = result.id;
                }
                return MemberScheduleActivityRepo.save(bookingData, transaction);
            });
    }

    static updateAttributes(conditionalAttributes, attributes, transaction = null) {
        const query = MemberScheduleActivityModel.query(transaction).update(attributes)
        for (let attribute of conditionalAttributes) {
            query[attribute.clause](attribute.key, attribute.value);
        }
        return query;
    }

    static cancelReserveActivity(user_id, member_ids, transaction = null) {
        return MemberScheduleActivityRepo.updateAttributes([{
            clause: "where",
            key: "modifiedby",
            value: user_id
        }, {
            clause: "whereIn",
            key: "member_id",
            value: member_ids
        }], {
            STATUS: "cancelled"
        }, transaction);
    }
}
