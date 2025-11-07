const BookActivityUseCase = require("../book-activity-usecase");
const ErrorTypes = require("../../../../errors/error-types");
const FetchPackageUseCase = require("../../../package/fetch-package-usecase");

module.exports = class BookFreeActivityUseCase extends BookActivityUseCase {
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
                return this.fetchPackageDetails();
            }).then(() => {
                return this.bookFreeActivity();
            }).then(() => {
                return this.releaseTableLockOnMemberScheduleActivity()
            }).then(() => {
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
                    "User is already assigned to this activity slot",
                    "BusinessError from validate function in BookFreeActivityUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateCustom()
    }

    validateCustom() {
        return this.checkIfUserIsNotRebookingTheSlotAgain()
            .then((errorList) => {
                return errorList.length ? errorList : this.checkIfScheduleDetailDateIsNotPassed()
            }).then((errorList) => {
                return errorList.length ? errorList : this.checkIfScheduleDetailSlotIsFull()
            });
    }

    fetchPackageDetails() {
        return this.fetchDefaultPackage()
            .then((packageDetails) => {
                return this.payLoad.package_details = packageDetails;
            });
    }

    fetchDefaultPackage() {
        return FetchPackageUseCase.fetchPackageByCode("DEFAULT_PACKAGE")
    }

    bookFreeActivity() {
        return this.saveMemberScheduleActivity(this.prepareDataForMemberScheduleActivity());
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
            STATUS: "booked",
            reminder: 0,
            ...(this.payLoad.type && {member_type: this.payLoad.type})
        }
    }
}
