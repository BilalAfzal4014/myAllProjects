const BookActivityUseCase = require("../book-activity-usecase");
const ErrorTypes = require("../../../../errors/error-types");
const SaveMemberPackageUseCase = require("../../../package/save-member-package-usecase");

module.exports = class TransformReserveToBookActivityUseCase extends BookActivityUseCase {

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
                return this.fetchMemberScheduleActivity();
            }).then(() => {
                return this.bookReservedActivity();
            }).then(() => {
                return this.updateMemberPackageCheckin();
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

    bookActivityWithoutTransaction() {
        return this.validate()
            .then(() => {
                return this.fetchMemberScheduleActivity();
            }).then(() => {
                return this.bookReservedActivity();
            }).then(() => {
                return this.updateMemberPackageCheckin();
            }).then(() => {
                this.sendEmailToMemberAndCompany();
            });
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book activity Validation Failed",
                    "BusinessError from validate function in TransformReserveToBookActivityUseCase"
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
            });
    }

    bookReservedActivity() {
        return this.saveMemberScheduleActivity(this.prepareDataForMemberScheduleActivity());
    }

    prepareDataForMemberScheduleActivity() {
        return {
            ...this.payLoad.member_schedule_activity_detail,
            schedule_detail_id: this.payLoad.schedule_detail_id,
            member_package_id: this.payLoad.member_package_id,
            package_id: this.payLoad.package_id,
            STATUS: "booked",
        }
    }

    updateMemberPackageCheckin() {
        this.incrementCheckInCount();
        return (new SaveMemberPackageUseCase(this.prepareDateForMemberPackage(), this.transactionInstance))
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