const _ = require("lodash");
const BaseUseCase = require("../../base/base-usecase");
const BookFreeActivityUseCase = require("../book-activity/free-activity/book-free-activity-usecase");
const BookReserveActivityUseCase = require("../book-activity/reserve-usecase/book-reserve-activity-usecase");
const BookPaidActivityUseCase = require("../book-activity/paid-activity/book-paid-activity-usecase");
const TransformReserveActivityUseCase = require("../book-activity/transform-activity/transform-reserve-to-book-activity-usecase");
const BookActivityConfigurationEntity = require("../../../entities/book-activity/book-activity-configuration/book-activity-configuration-entity");
const ErrorTypes = require("../../../errors/error-types");
const Validator = require("../../../entity-validations/validator");
const FetchScheduleDetailUseCase = require("../../activity/fetch-schedule-detail-usecase");
const FetchMemberScheduleActivityUseCase = require("../../member-schedule-activity/Fetch-member-schedule-activity-usecase");

module.exports = class BookActivityConfigurationUseCase extends BaseUseCase {

    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
        this.bookActivityConfigurationEntityInstance = new BookActivityConfigurationEntity();
        this.bookActivityUseCaseInteractor = null;
    }

    get DesiredBookActivityUseCaseInteractor() {
        return this.bookActivityUseCaseInteractor;
    }

    bookActivity() {
        return this.validate()
            .then(() => {
                return this.initializeRequiredBookActivityUseCase();
            }).then(() => {
                return this.bookActivityThroughRequiredBookActivityInteractor();
            });
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book activity Validation Failed",
                    "BusinessError from validate function in BookActivityConfigurationUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields()
            .then((errorList) => {
                return errorList.length ? errorList : this.validateCustom();
            });
    }

    validateUserProvidedFields() {
        this.payLoad = _.pick(this.payLoad, this.bookActivityConfigurationEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, null);

        return this.validatorInstance.validate(
            this.bookActivityConfigurationEntityInstance.getValidationRules(),
            this.bookActivityConfigurationEntityInstance.getFieldsForUniqueness()
        );
    }

    validateCustom() {
        return this.checkIfScheduleDetailsExist()
    }

    checkIfScheduleDetailsExist() {
        return this.fetchScheduleDetail()
            .then((scheduleDetail) => {
                return scheduleDetail ? [] : [{
                    field: "schedule_detail_id",
                    error: "Schedule detail doesn't exist"
                }];
            })
    }

    fetchScheduleDetail() {
        return FetchScheduleDetailUseCase.fetchScheduleDetailWithItsActivityById(this.payLoad.schedule_detail_id)
            .then((scheduleDetail) => {
                return this.payLoad.schedule_detail = scheduleDetail;
            });
    }

    initializeRequiredBookActivityUseCase() {

        if (this.checkIfActivityIsFree()) {
            return this.bookActivityUseCaseInteractor = new BookFreeActivityUseCase(this.payLoad);
        } else if (this.checkIfWantToReserveActivity()) {
            return this.bookActivityUseCaseInteractor = new BookReserveActivityUseCase(this.payLoad);
        } else {
            return this.makeDecisionOfInitializationAmongRemainingUseCases()
                .then(() => {
                    return this.handleErrorIfStillNoBookActivityInteractorAssigned();
                });
        }
    }

    checkIfActivityIsFree() {
        return this.payLoad.schedule_detail.is_free;
    }

    checkIfWantToReserveActivity() {
        return this.payLoad.member_package_id === "N/A";
    }

    makeDecisionOfInitializationAmongRemainingUseCases() {
        return this.fetchMemberScheduleActivity()
            .then((memberScheduleActivity) => {
                if (!memberScheduleActivity) {
                    return this.bookActivityUseCaseInteractor = new BookPaidActivityUseCase(this.payLoad);
                } else if (!memberScheduleActivity.member_package_id && memberScheduleActivity.STATUS === "reserved") {
                    return this.bookActivityUseCaseInteractor = new TransformReserveActivityUseCase(this.payLoad);
                } else {
                    return null;
                }
            });
    }

    handleErrorIfStillNoBookActivityInteractorAssigned() {
        if (!this.bookActivityUseCaseInteractor) {
            this.handleErrorIfExist(
                [{
                    meta: {
                        user_id: this.payLoad.user_id
                    },
                    field: "schedule_id",
                    error: "User is already assigned to this activity slot"
                }],
                ErrorTypes.MISSING_ATTRIBUTES,
                "Book activity Validation Failed",
                "BusinessError from validate function in BookActivityConfigurationUseCase"
            );
        }
    }

    fetchMemberScheduleActivity() {
        return FetchMemberScheduleActivityUseCase.fetchSpecificBookedOrReservedMSAOfAMemberByMemberAndScheduleDetailId(
            this.payLoad.user_id,
            this.payLoad.schedule_detail_id
        ).then(([memberScheduleActivity]) => {
            return this.payLoad.member_schedule_activity_detail = memberScheduleActivity;
        });
    }

    bookActivityThroughRequiredBookActivityInteractor() {
        return this.bookActivityUseCaseInteractor.bookActivity();
    }

}
