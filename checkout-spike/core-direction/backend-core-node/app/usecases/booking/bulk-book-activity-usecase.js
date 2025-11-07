const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const ActivityBookingEntity = require("../../entities/activity-booking/activity-booking-entity");
const ErrorTypes = require("../../errors/error-types");
const ActivityScheduleRepo = require("../../repositories/activityScheduleRepo");
const PackageRepo = require("../../repositories/packageRepo");
const ScheduleDetailRepo = require("../../repositories/scheduleDetailRepo");
const ActivitySchedulePackageRepo = require("../../repositories/activitySchedulePackageRepo");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const Validator = require("../../entity-validations/validator");
const GeneralHelper = require("../../helpers/general-helper");
const BookingEmail = require("../booking/booking-email")
const BookingModuleConstants = require('../../constants/booking-module')
const CompanyModuleConstants = require('../../constants/company-module')
module.exports = class BulkBookActivityUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.class_id = payload.class_id;
        this.sendActivationEmail = false;
        this.slot_id = payload.slot_id;
        this.package_id = (payload.package_id) ? payload.package_id : null;

        this.users = payload.users;
        this.memberScheduleActivity = null;

        this.gifterData = {
            'g_email': payload.user_id.email,
            'g_name': payload.user_id.firstname + " " + payload.user_id.lastname
        };
        this.user = null;

        this.booking_statuses = []

        this.activityBookingEntityInstance = new ActivityBookingEntity();
        this.validatorInstance = null;
    }

    proceedNextWithError(message) {
        this.booking_statuses = [...this.booking_statuses, {status: false, message: message, email: this.user.email}]
        return;
    }

    proceedNextWithSuccess() {
        this.booking_statuses = [...this.booking_statuses, {status: true, email: this.user.email}]
        return;
    }

    bookActivity() {
        return this.startProcedure();
    }

    async startProcedure() {
        for (let i = 0; i < this.users.length; i++) {
            this.user = this.users[i];
            const user = await this.fetchUserIfExist();
            if (user) {
                this.sendActivationEmail = false;
                this.user = user;
                await this.startUserBooking();
            } else {
                this.sendActivationEmail = true;
                await this.startUserRegistration();
            }
        }
        return this.booking_statuses;
    }

    fetchUserIfExist() {
        return FosUserUserRepo.findByEmail(this.user.email);
    }

    startUserRegistration() {
        return this.validateUserDataForRegistration();
    }

    validateUserDataForRegistration() {
        if (!this.user.first_name || this.user.first_name == '') {
            this.proceedNextWithError(BookingModuleConstants.F_NAME_RE)
        } else if (!this.user.last_name || this.user.last_name == '') {
            this.proceedNextWithError(BookingModuleConstants.L_NAME_RE)
        } else if (!this.user.email || this.user.email == '') {
            this.proceedNextWithError(BookingModuleConstants.EMAIL_RE)
        } else {
            return this.performUserRegistration()
        }
    }

    performUserRegistration() {
        return this.getTransactionInstance()
            .then(() => {
                return this.saveUser();
            }).then((user) => {
                this.commitTransaction();
                return user;
            }).then((user) => {
                this.user = user
                return this.startUserBooking();
            }).catch((error) => {
                this.rollbackTransaction();
                this.proceedNextWithError(BookingModuleConstants.ROLL_BACK_TRANS)
                throw error;
            });
    }

    saveUser() {
        return FosUserUserRepo.save(this.getRegisterUserTableData(), this.transactionInstance);
    }

    getRegisterUserTableData() {
        return {
            firstname: this.user.first_name,
            lastname: this.user.last_name,
            email: this.user.email,
            username: this.user.email,
            username_canonical: this.user.email,
            email_canonical: this.user.email,
            password: process.env.temp_user_password,
            phone: this.user.phone,
            enabled: 0,
            salt: 10,
            roles: 'a:0:{}',
            confirmation_token: this.generateToken(),
            is_gdpr: 1
        }
    }

    startUserBooking() {
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
        // we are only booking if user have free package.
        return ActivityScheduleRepo.fetchActivityScheduleAgaintClassId(this.class_id).then((record) => {
            if (record.is_free) {
                return this.fetchDefaultPacakge()
            } else {
                this.proceedNextWithError(BookingModuleConstants.NO_FREE_PACKAGE);
            }
        })
    }

    fetchDefaultPacakge() {
        return PackageRepo.findDefaultPackage().then((record) => {
            if (record) {
                this.package_id = record.id;
                return this.fetchAvailableSlots()
            } else {
                this.proceedNextWithError(BookingModuleConstants.NO_FREE_PACKAGE)
            }
        })
    }


    fetchAvailableSlots() {
        return MemberScheduleActivityRepo.fetchAvailableSlots(this.slot_id).then((available) => {
            return (available) ? this.startBookingProcess() : this.proceedNextWithError(BookingModuleConstants.NO_SLOT_AVAILABLE);
        })
    }

    startBookingProcess() {
        return MemberScheduleActivityRepo.checkIfUserAlreadyHasBooking(this.user.id, this.slot_id).then((bookings) => {
            return (bookings.length > 0) ? this.proceedNextWithError(BookingModuleConstants.CLASS_BOOKED) : this.performSaveBookingAction();
        })
    }


    performSaveBookingAction() {
        return this.getTransactionInstance()
            .then(() => {
                return this.saveBooking();
            }).then((data)=>{

                this.memberScheduleActivity = data.id;
                return this.commitTransaction();

            }).then(() => {
                return this.sendEmail(this.memberScheduleActivity)
            }).then(() => {
                this.proceedNextWithSuccess()
            }).catch((error) => {
                console.log(error)
                this.rollbackTransaction();
                this.proceedNextWithError(BookingModuleConstants.ROLL_BACK_TRANS)
                throw error;
            });
    }

    saveBooking() {
        return MemberScheduleActivityRepo.save(this.getMemberScheduleActivityTableData(), this.transactionInstance);
    }

    sendEmail(memberScheduleActivity){

        const bookingEmail = new BookingEmail(memberScheduleActivity,this.sendActivationEmail,this.gifterData,true);
        return bookingEmail.sendEmail();
    }
    getMemberScheduleActivityTableData() {
        return {
            member_id: this.user.id,
            schedule_detail_id: this.slot_id,
            package_id: this.package_id,
            checkin: 0,
            modifiedby: this.user.id,
            is_favourite: 0,
            reminder: 0,
            is_deleted: 0,
            STATUS: CompanyModuleConstants.STATUS_BOOKED
        }
    }

    sendErrorIfAnyForValidation(errorList) {
        return this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            BookingModuleConstants.BOOKING_VALIDATION,
            "BusinessError from validate function in BookActivityUseCase"
        );
    }

}
