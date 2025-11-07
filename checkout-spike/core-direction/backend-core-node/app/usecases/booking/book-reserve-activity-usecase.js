const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const ActivityBookingEntity = require("../../entities/activity-booking/activity-booking-entity");
const ErrorTypes = require("../../errors/error-types");
const ActivityScheduleRepo = require("../../repositories/activityScheduleRepo");
const BookingEmail = require("../booking/booking-email");
const PackageRepo = require("../../repositories/packageRepo");
const ScheduleDetailRepo = require("../../repositories/scheduleDetailRepo");
const ActivitySchedulePackageRepo = require("../../repositories/activitySchedulePackageRepo");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const Validator = require("../../entity-validations/validator");
const BookingModuleConstants = require('../../constants/booking-module')
const CompanyModuleConstants = require('../../constants/company-module')
module.exports = class BookReserveActivityUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.class_id = payload.class_id;
        this.slot_id = payload.slot_id;
        this.member_id = payload.user_id;
        this.memberScheduleActivity = null;
        this.package_id = (payload.package_id) ? payload.package_id : null;
        this.activityBookingEntityInstance = new ActivityBookingEntity();
        this.validatorInstance = null;
    }

    bookReserveActivity() {
        return this.validate()
            .then(() => {
                return this.fetchActivityScheduleAgaintClassId()
            })
    }

    validate() {
        return this.validateClassIdForActivitySchedule()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
                return this.validateSlotIdForActivitySchedule()
            }).then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }

    validateClassIdForActivitySchedule() {
        this.validatorInstance = new Validator({class_id: this.class_id}, ActivityScheduleRepo, false);
        return this.validatorInstance.validate(
            this.activityBookingEntityInstance.getValidationRulesForClassId(),
            this.activityBookingEntityInstance.getFieldsForUniqueness(),
        );
    }

    validateSlotIdForActivitySchedule() {
        this.validatorInstance = new Validator({slot_id: this.slot_id}, ScheduleDetailRepo, false);
        return this.validatorInstance.validate(
            this.activityBookingEntityInstance.getValidationRulesForSlotId(),
            this.activityBookingEntityInstance.getFieldsForUniqueness(),
        );
    }

    fetchActivityScheduleAgaintClassId() {
        return ActivityScheduleRepo.fetchActivityScheduleAgaintClassId(this.class_id).then((record) => {
            if (record.is_free) {
                return this.fetchDefaultPacakge()
            } else {
                return (this.package_id) ? this.fetchUserPacakge() : this.sendErrorIfAnyForValidation([{
                    "field": "package_id",
                    "error": "package id is required"
                }])
            }
        })
    }

    fetchDefaultPacakge() {
        return PackageRepo.findDefaultPackage().then((record) => {
            if (record) {
                this.package_id = record.id;
                return this.fetchAvailableSlots()
            } else {
                this.sendErrorIfAnyForValidation([{
                    "field": "package_id",
                    "error": "package id is required"
                }])
            }
        })
    }

    fetchUserPacakge() {
        return ActivitySchedulePackageRepo.fetchUserPackage(this.package_id).then((record) => {
            return record.length > 0 ? this.fetchAvailableSlots() : this.sendErrorIfNoActivePackageForBooking()
        })
    }

    fetchAvailableSlots() {

        return MemberScheduleActivityRepo.fetchAvailableSlots(this.slot_id).then((available) => {
            return (available) ? this.startBookingProcess() : this.sendErrorIfNoSlotForBooking();
        })
    }

    startBookingProcess() {

        return MemberScheduleActivityRepo.checkIfUserAlreadyHasBooking(this.member_id, this.slot_id).then((bookings) => {
            return (bookings.length > 0) ? this.sendErrorClassAlreadyBooked() : this.performSaveBookingAction();
        })
    }


    performSaveBookingAction() {
        return this.getTransactionInstance()
            .then(() => {
                return this.saveBooking();
            }).then((data) => {
                this.memberScheduleActivity = data.id;
                return this.commitTransaction();
            }).then(() => {
                return this.sendEmail()
                    .then(() => {
                        return {message: BookingModuleConstants.BOOKING_SUCCESSFULL};

                    })
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    sendEmail() {

        const bookingEmail = new BookingEmail(this.memberScheduleActivity);
        return bookingEmail.sendEmail();
    }

    saveBooking() {
        return MemberScheduleActivityRepo.save(this.getMemberScheduleActivityTableData(), this.transactionInstance);
    }

    getMemberScheduleActivityTableData() {
        return {
            member_id: this.member_id,
            schedule_detail_id: this.slot_id,
            package_id: this.package_id,
            checkin: 0,
            modifiedby: this.member_id,
            is_favourite: 0,
            reminder: 0,
            is_deleted: 0,
            STATUS: CompanyModuleConstants.STATUS_RESERVED
        }
    }

    sendErrorIfAnyForValidation(errorList) {
        return this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.BOOKING_VALIDATION,
            "BusinessError from validate function in BookActivityUseCase"
        );
    }

    sendErrorIfNoActivePackageForBooking() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.NO_BOOKING_PACKAGE,
            "BusinessError from validate function in BookActivityUseCase"
        );
    }

    sendErrorIfNoSlotForBooking() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.NO_SLOT_AVAILABLE,
            "BusinessError from validate function in BookActivityUseCase"
        );
    }

    sendErrorClassAlreadyBooked() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.CLASS_BOOKED,
            "BusinessError from validate function in BookActivityUseCase"
        );
    }

    sendErrorNoDefaultPackageFound() {
        return this.handleErrorIfExist([{
                "field": "",
                "error": ""
            }],
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.NO_FREE_PACKAGE,
            "BusinessError from validate function in BookActivityUseCase"
        );
    }
}
