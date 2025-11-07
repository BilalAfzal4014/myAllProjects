const BookActivityUseCase = require("../book-activity-usecase");
const ErrorTypes = require("../../../../errors/error-types");

module.exports = class BookReserveActivityUseCase extends BookActivityUseCase {
    constructor(payLoad) {
        super(payLoad);
    }

    bookActivity() {
        return this.getTransactionInstance()
            .then(() => {
                return this.acquireTableLockOnMemberScheduleActivity();
            }).then(() => {
                return this.validate();
            }).then(() => {
                return this.reserveActivity();
            }).then(() => {
                return this.releaseTableLockOnMemberScheduleActivity()
            }).then(() => {
                return this.commitTransaction();
            }).catch((error) => {
                this.releaseTableLockAndRollbackTransaction();
                throw error;
            });
    }

    bookActivityWithoutTransaction() {
        return this.reserveActivity();
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book activity Validation Failed",
                    "BusinessError from validate function in BookReserveActivityUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateCustom()
    }

    validateCustom() {
        return this.checkIfPackageExist()
            .then((errorList) => {
                return errorList.length ? errorList : this.checkIfScheduleDetailDateIsNotPassed()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfUserIsNotRebookingTheSlotAgain()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfScheduleDetailSlotIsFull()
            });
    }

    checkIfUserIsNotRebookingTheSlotAgain() {
        return this.fetchMemberScheduleActivity()
            .then((memberScheduleActivity) => {
                return memberScheduleActivity ? [{
                    ...(memberScheduleActivity.STATUS !== "reserved" && {
                        meta: {
                            user_id: this.payLoad.user_id
                        }
                    }),
                    field: "schedule_id",
                    error: memberScheduleActivity.STATUS === "reserved"
                        ? "User slot is already reserved"
                        : "User is already assigned to this activity slot"
                }] : [];
            });
    }

    reserveActivity() {
        return this.upsertMemberScheduleActivity(this.prepareDataForMemberScheduleActivity());
    }

    prepareDataForMemberScheduleActivity() {
        return {
            member_id: this.payLoad.user_id,
            schedule_detail_id: this.payLoad.schedule_detail_id,
            is_deleted: 0,
            modifiedby: this.payLoad.modified_by,
            checkin: 0,
            package_id: this.payLoad.package_details.id,
            is_favourite: 0,
            STATUS: "reserved",
            reminder: 0,
            ...(this.payLoad.type && {member_type: this.payLoad.type})
        }
    }
}