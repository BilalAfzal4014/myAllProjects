const _ = require("lodash");
const MemberScheduleActivityEntity = require("../../entities/memeber-schedule-activity/member-schedule-activity-entity");
const Validator = require("../../entity-validations/validator");
const MemberScheduleActivityRepo = require("../../repositories/memberScheduleActivityRepo");
const BaseUseCase = require("../base/base-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class SaveMemberScheduleActivityUseCase extends BaseUseCase {

    constructor(memberScheduleActivity, transaction = null) {
        super(transaction);
        this.memberScheduleActivity = memberScheduleActivity;
        this.memberScheduleActivityEntityInstance = new MemberScheduleActivityEntity(memberScheduleActivity);
    }

    saveMemberScheduleActivity() {
        return this.validate()
            .then(() => {
                return this.performSaveAction();
            });
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Member schedule activity Validation Failed",
                    "BusinessError from validate function in SaveMemberScheduleActivityUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.memberScheduleActivity = _.pick(this.memberScheduleActivity, this.memberScheduleActivityEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.memberScheduleActivity, MemberScheduleActivityRepo);

        return this.validatorInstance.validate(
            this.memberScheduleActivityEntityInstance.getValidationRules(),
            this.memberScheduleActivityEntityInstance.getFieldsForUniqueness()
        );
    }

    performSaveAction() {
        return this.save();
    }

    performUpsertAction(){
        return this.upsert();
    }

    save() {
        return MemberScheduleActivityRepo.save(this.memberScheduleActivity, this.transactionInstance);
    }

    upsert(){
        return MemberScheduleActivityRepo.upsert(this.memberScheduleActivity, this.transactionInstance);
    }
}