const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const MemberPackageRepo = require("../../repositories/memberPackageRepo");
const BookingModuleConstants = require("../../constants/booking-module");
const CompanyModuleConstants = require("../../constants/company-module");
const ErrorTypes = require("../../errors/error-types");
const BookingEmail = require("../booking/booking-email")

module.exports = class CancelActivityUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.user_id = payload.user_id;
        this.slot_id = payload.slot_id;
    }

    cancelActivity() {
        return this.checkIsActivityExistsAndBooked();
    }

    checkIsActivityExistsAndBooked() {
        return MemberScheduleActivityRepo.checkIsActivityExistsAndBooked({
            member_id: this.user_id,
            slot_id: this.slot_id
        }).then((exists) => {
            return (exists) ? this.cancelActivityBooking() : this.sendErrorBookedActivityNotfound();
        })
    }

    cancelActivityBooking() {
        return MemberScheduleActivityRepo.save({
            id: this.slot_id,
            member_id: this.user_id,
            STATUS: CompanyModuleConstants.STATUS_CANCELLED,
            is_deleted: 0
        }).then((record) => {
            if (record.member_package_id != null) {
                return MemberPackageRepo.decCheckin(record.member_package_id).then((rec) => {
                    // return [{'message': BookingModuleConstants.BOOKING_CANCELLED}];
                    return this.sendEmailNotification()
                        .then(() => {
                            return [{'message': BookingModuleConstants.BOOKING_CANCELLED}];
                        })
                });
            } else {
                // return [{'message': BookingModuleConstants.BOOKING_CANCELLED}];
                return this.sendEmailNotification()
                    .then(() => {
                        return [{'message': BookingModuleConstants.BOOKING_CANCELLED}];
                    })
            }
        })
    }

    sendEmailNotification() {
        const bookingEmail = new BookingEmail(this.slot_id);
        return bookingEmail.bookingCancellationEmail();

    }

    sendErrorBookedActivityNotfound() {
        return this.handleErrorIfExist([
                {
                    "field": "",
                    "error": ""
                }
            ],
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.BOOKING_NOT_FOUND,
            ""
        );
    }
}
