const BaseUseCase = require("../../base/base-usecase");
const FetchActivitySchedulePackageUseCase = require("../../activity/fetch-activity-schedule-package-usecase");
const FetchMemberScheduleActivityUseCase = require("../../member-schedule-activity/Fetch-member-schedule-activity-usecase");
const SaveMemberScheduleActivityUseCase = require("../../member-schedule-activity/save-member-schedule-activity-usecase");
const FetchMemberPackageUseCase = require("../../package/get-member-packages-usecase");
const FetchPackageUseCase = require("../../package/fetch-package-usecase");
const GeneralHelper = require("../../../helpers/general-helper");
const BookingEmail = require("../booking-email");

module.exports = class BookActivityUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
    }

    acquireTableLockOnMemberScheduleActivity() {
        return Promise.resolve('true');
        // return FetchMemberScheduleActivityUseCase.acquireLockOnTable(this.transactionInstance);
    }

    releaseTableLockOnMemberScheduleActivity() {
        return Promise.resolve(true);
        // return FetchMemberScheduleActivityUseCase.unLockTable(this.transactionInstance);
    }

    checkIfPackageExist() {
        return this.fetchPackageDetails()
            .then((packageDetails) => {
                return packageDetails ? [] : [{
                    field: "package_id",
                    error: "Package doesn't exist"
                }];
            });
    }

    fetchPackageDetails() {
        return FetchPackageUseCase.fetchPackageById(this.payLoad.package_id)
            .then((packageDetails) => {
                return this.payLoad.package_details = packageDetails;
            });
    }

    updateMemberPackageCheckin() {
        this.incrementCheckInCount();
        return (new SaveMemberPackageUseCase(this.prepareDateForMemberPackage(), null/*this.transactionInstance*/))
            .saveMemberPackage();
    }

    incrementCheckInCount() {
        this.payLoad.member_package_details.checkin += 1;
    }

    checkIfMemberPackageAssociatedWithPackage() {
        return this.fetchMemberPackage()
            .then((memberPackageDetails) => {
                return memberPackageDetails ? [] : [{
                    field: "member_package_id",
                    error: "Either member package is not active or not associated with desired package or user or doesn't exist at all"
                }];
            });
    }


    checkIfCheckInsAvailable() {
        return this.compareIfCheckinReachedVisits() ? [{
            field: "member_package_id",
            error: "Checkin limit reached"
        }] : [];
    }

    compareIfCheckinReachedVisits() {
        return parseInt(this.payLoad.member_package_details.checkin) >=
            parseInt(this.payLoad.package_details.visits)
    }

    checkIfPackageAssignedToTheActivity() {
        return this.fetchSpecificPackageAndItsSpecificActivity()
            .then((packageActivity) => {
                return packageActivity ? [] : [{
                    field: "package_id",
                    error: "This package is not assigned to this activity"
                }];
            })
    }

    checkIfUserIsNotRebookingTheSlotAgain() {
        return this.fetchMemberScheduleActivity()
            .then((memberScheduleActivity) => {
                return memberScheduleActivity ? [{
                    meta: {
                        user_id: this.payLoad.user_id
                    },
                    field: "schedule_id",
                    error: "User is already assigned to this activity slot"
                }] : [];
            });
    }

    checkIfScheduleDetailDateIsNotPassed() {
        const todayDate = new Date();
        const xMinutesAheadDateFromRequiredDate = GeneralHelper.addXMinutesToCurrentDateTime(this.payLoad.schedule_detail.duration, (new Date(this.payLoad.schedule_detail.schedule_date)));
        return todayDate > xMinutesAheadDateFromRequiredDate ? [{
            field: "schedule_id",
            error: "The schedule date has passed for this slot"
        }] : [];
    }

    checkIfScheduleDetailSlotIsFull() {
        return FetchMemberScheduleActivityUseCase.checkIfSlotLeft(this.payLoad.schedule_detail_id, [this.payLoad.user_id], this.transactionInstance)
            .then((spaceAvailable) => {
                return spaceAvailable ? [] : [{
                    field: "schedule_detail_id",
                    error: "All Slots are booked not space left"
                }]
            });
    }

    fetchSpecificPackageAndItsSpecificActivity() {
        return FetchActivitySchedulePackageUseCase.fetchPackageAndItsSpecificActivity(this.payLoad.package_id, this.payLoad.schedule_detail.schedule_id);
    }

    fetchMemberScheduleActivity() {
        return FetchMemberScheduleActivityUseCase.fetchSpecificBookedOrReservedMSAOfAMemberByMemberAndScheduleDetailId(this.payLoad.user_id, this.payLoad.schedule_detail_id, this.transactionInstance)
            .then(([memberScheduleActivity]) => {
                return this.payLoad.member_schedule_activity_detail = memberScheduleActivity;
            });
    }

    saveMemberScheduleActivity(memberScheduleActivity) {
        return (new SaveMemberScheduleActivityUseCase(memberScheduleActivity, this.transactionInstance)).performSaveAction();
    }

    upsertMemberScheduleActivity(memberScheduleActivity) {
        return (new SaveMemberScheduleActivityUseCase(memberScheduleActivity, this.transactionInstance)).performUpsertAction();
    }

    fetchMemberPackage() {
        return FetchMemberPackageUseCase.fetchSpecificActivePackageOfAMemberByIdWithWriteLock(
            this.payLoad.member_package_id,
            this.payLoad.package_id,
            this.payLoad.user_id,
            // null
            this.transactionInstance
        ).then(([memberPackageDetails]) => {
            return this.payLoad.member_package_details = memberPackageDetails;
        });
    }

    releaseTableLockAndRollbackTransaction() {
        return this.releaseTableLockOnMemberScheduleActivity()
            .then(() => {
                return this.rollbackTransaction();
            });
    }

    sendEmailToMemberAndCompany() {

        return this.getTransactionInstance()
            .then(() => {
                return this.getBookingDataForSendingEmail()
                    .then((data) => {
                        if (data) {
                            const bookingEmail = new BookingEmail(data.id);
                            return bookingEmail.sendEmail();
                        } else {
                            return Promise.reject('There is some issue while sending email')
                        }
                    })
            }).then(() => {
                return this.commitTransaction();
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            })

    }

    getBookingDataForSendingEmail() {

        return this.fetchMemberScheduleActivity()
            .then((data) => {
                console.log(data)
                if (data) {
                    if (data.STATUS == 'booked') {

                        return data;
                    } else {
                        return null;
                    }
                }
            })
    }
}