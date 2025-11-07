const BookActivityUseCase = require("../book-activity-usecase");
const ErrorTypes = require("../../../../errors/error-types");
const SaveMemberPackageUseCase = require("../../../package/save-member-package-usecase");

module.exports = class BookPaidActivityUseCase extends BookActivityUseCase {

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
                return this.bookPaidActivity();
            }).then(() => {
                return this.updateMemberPackageCheckin();
            }).then(() => {
                return this.releaseTableLockOnMemberScheduleActivity()
            }).then(async () => {
                return this.commitTransaction();
            }).then(() => {
                this.sendEmailToMemberAndCompany();
            }).catch((error) => {
                this.releaseTableLockAndRollbackTransaction();
                throw error;
            });
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book activity Validation Failed",
                    "BusinessError from validate function in BookPaidActivityUseCase"
                );
            })
    }

    validateWithoutThrowingError() {
        return this.validateCustom()
    }

    validateCustom() {
        return this.checkIfPackageExist()
            .then(() => {
                return this.checkIfMemberPackageAssociatedWithPackage();
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfCheckInsAvailable()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfPackageAssignedToTheActivity()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfScheduleDetailDateIsNotPassed()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfUserIsNotRebookingTheSlotAgain()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfScheduleDetailSlotIsFull()
            });
    }

    bookPaidActivity() {
        return this.saveMemberScheduleActivity(this.prepareDataForMemberScheduleActivity());
    }

    prepareDataForMemberScheduleActivity() {
        return {
            member_id: this.payLoad.user_id,
            schedule_detail_id: this.payLoad.schedule_detail_id,
            is_deleted: 0,
            modifiedby: this.payLoad.modified_by,
            checkin: 0,
            package_id: this.payLoad.package_id,
            is_favourite: 0,
            member_package_id: this.payLoad.member_package_id,
            STATUS: "booked",
            reminder: 0,
            ...(this.payLoad.type && {member_type: this.payLoad.type})
        }
    }

    updateMemberPackageCheckin() {
        this.incrementCheckInCount();
        return (new SaveMemberPackageUseCase(this.prepareDateForMemberPackage(),this.transactionInstance))
            .saveMemberPackage();
    }

    incrementCheckInCount() {
        this.payLoad.member_package_details.checkin += 1;
    }

    prepareDateForMemberPackage() {
        return {
            ...this.payLoad.member_package_details,
            status: this.compareIfCheckinReachedVisits() ? "expired" : "active"
        }
    }
}