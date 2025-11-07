const _ = require("lodash");
const BaseUseCase = require("../../../base/base-usecase");
const SingleBookingUseCase = require("./single-booking-usecase");
const Validator = require("../../../../entity-validations/validator");
const MultipleActivityEntity = require("../../../../entities/book-activity/multiple-activity/multiple-activity-entity");
const FetchMemberScheduleActivityUseCase = require("../../../member-schedule-activity/Fetch-member-schedule-activity-usecase");
const ErrorTypes = require("../../../../errors/error-types");
const ChainedDataHelper = require("../../../../helpers/chained-data-helper");
const FetchMemberPackageUseCase = require("../../../package/get-member-packages-usecase");

module.exports = class MultipleAbsoluteBookingUseCase extends BaseUseCase {

    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
        this.singleBookingInteractors = [];
        this.multipleActivityEntityInstance = new MultipleActivityEntity();
        this.totalReserved = 0;
    }

    bookActivity() {
        return this.getTransactionInstance()
            .then(() => {
                return this.validatePreReqs()
            }).then(() => {
                return this.checkForUsersExistence();
            }).then(() => {
                return this.validatePostReqs();
            }).then(() => {
                return this.performBookActivitiesAction();
            }).then(() => {
                return this.commitTransaction();
            }).then(() => {
                return this.payLoad.users;
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    validatePreReqs() {
        return this.validatePreReqsWithoutThrowingErrors()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book absolute multiple activity Validation Failed",
                    "BusinessError from validatePreReqs function in MultipleAbsoluteBookingUseCase"
                );
            });
    }

    validatePreReqsWithoutThrowingErrors() {
        return this.validateUserProvidedFields()
            .then((errorList) => {
                return errorList.length ? errorList : this.validateDuplicationOfUsers();
            }).then((errorList) => {
                return errorList.length ? errorList : this.validateMixtureOfReservedAndBooking();
            }).then((errorList) => {
                return errorList.length ? errorList : this.validateIfAllThePackagesArePurchasedByCurrentUser();
            }).then((errorList) => {
                return errorList.length ? errorList : this.validateSingleBookingPreReqs();
            });
    }

    validateUserProvidedFields() {
        this.payLoad = _.pick(this.payLoad, this.multipleActivityEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, null);

        return this.validatorInstance.validate(
            this.multipleActivityEntityInstance.getValidationRules(),
            this.multipleActivityEntityInstance.getFieldsForUniqueness()
        );
    }

    validateDuplicationOfUsers() {
        const emailHash = {};
        for (const user of this.payLoad.users) {
            user.email = user.email.toLowerCase();
            if (user.type !== "ForUnder18" && emailHash[user.email]) {
                return [{
                    field: "email",
                    error: "There are two or more users with the same email"
                }];
            }
            if (user.type !== "ForUnder18")
                emailHash[user.email] = true;
        }
        return [];
    }

    validateMixtureOfReservedAndBooking() {
        for (const user of this.payLoad.users) {
            if (user.member_package_id === "N/A") {
                this.totalReserved++;
            }
        }
        return (this.totalReserved === 0 || this.totalReserved === this.payLoad.users.length) ? [] : [{
            field: `member_package_id`,
            error: `Few of the user(s) have already booked the activity`
        }];
    }

    validateIfAllThePackagesArePurchasedByCurrentUser() {
        if (this.totalReserved === 0) {
            const memberPackageIds = this.payLoad.users.map((user) => user.member_package_id ? user.member_package_id : -1);
            return FetchMemberPackageUseCase.fetchMemberPackagesByIdsAndModifiedBy(memberPackageIds, this.payLoad.user_id, "active")
                .then((memberPackages) => {
                    return memberPackages.length === this.payLoad.users.length ? [] : [{
                        field: "member_package_id",
                        error: "Can't use the packages not purchased by you"
                    }];
                });
        }
        return Promise.resolve([]);
    }

    validateAvailableSlotCount() {
        return this.fetchAvailableSlots()
            .then((availableSlots) => {
                return availableSlots >= this.payLoad.users.length ? [] : [{
                    field: "users",
                    error: `Only ${availableSlots} slot(s) is/are available for this class`
                }];
            });
    }

    fetchAvailableSlots() {
        const includingUsers = this.payLoad.users.map((user) => user.user_id);
        return FetchMemberScheduleActivityUseCase.fetchAvailableSlots(this.payLoad.schedule_detail_id, includingUsers);
    }

    validateSingleBookingPreReqs() {
        const validationPromises = [];
        for (const user of this.payLoad.users) {
            const singleBookingInteractor = new SingleBookingUseCase({
                ...user,
                modified_by: this.payLoad.user_id,
                schedule_detail_id: this.payLoad.schedule_detail_id
            }, this.payLoad.user_id);
            validationPromises.push(singleBookingInteractor.validatePreReqsWithoutThrowingErrors());
            this.singleBookingInteractors.push(singleBookingInteractor)
        }
        return Promise.all(validationPromises).then((errorList) => ChainedDataHelper.convertNestedToFlatArray(errorList));
    }

    checkForUsersExistence() {
        const UsersExistencePromises = [];
        for (const singleBookingInteractor of this.singleBookingInteractors) {
            UsersExistencePromises.push(singleBookingInteractor.checkForUserExistence());
        }
        return Promise.all(UsersExistencePromises)
            .then((userIds) => {
                return this.fillUpUserIds(userIds);
            });
    }

    fillUpUserIds(userIds) {
        let index = 0;
        for (const user of this.payLoad.users) {
            user.user_id = userIds[index];
            index++;
        }
    }

    validatePostReqs() {
        return this.validatePostReqsWithoutThrowingErrors()
            .then((errorList) => {
                this.shouldThroughError(errorList) && this.handleErrorIfExist(
                    this.insertEmailIntoErrors(errorList),
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book absolute multiple activity Validation Failed",
                    "BusinessError from validatePostReqs function in MultipleAbsoluteBookingUseCase",
                    {users: this.payLoad.users}
                );
            });
    }

    validatePostReqsWithoutThrowingErrors() {
        return this.validateAvailableSlotCount()
            .then((errorList) => {
                return errorList.length ? errorList : this.validatePostPreReqsForEachUser();
            });
    }

    validatePostPreReqsForEachUser() {
        const validationPromises = [];
        for (const singleBookingInteractor of this.singleBookingInteractors) {
            validationPromises.push(singleBookingInteractor.validatePostReqsWithoutThrowingErrors());
        }
        return Promise.all(validationPromises).then((errorList) => ChainedDataHelper.convertNestedToFlatArray(errorList));
    }

    shouldThroughError(errorList) {
        //removing all reserve slot errors, because user can still reserve the activity again if not booked
        let index = 0;
        for (index; index < errorList.length; index++) {
            if (errorList[index].error === "User slot is already reserved") {
                errorList.splice(index, 1);
                index--;
            }
        }
        return !!errorList.length;
    }

    insertEmailIntoErrors(errorList) {
        for (const error of errorList) {
            if (error.meta && error.meta.user_id) {
                for (const user of this.payLoad.users) {
                    if (user.user_id === error.meta.user_id) {
                        error.meta.email = user.email;
                        error.error = `${user.email} ${error.error}`
                    }
                }
            }
        }
        return errorList;
    }

    performBookActivitiesAction() {
        const bookActivitiesPromises = [];
        for (const singleBookingInteractor of this.singleBookingInteractors) {
            bookActivitiesPromises.push(singleBookingInteractor.performBookActivityAction(false));
        }
        return Promise.all(bookActivitiesPromises);
    }

}