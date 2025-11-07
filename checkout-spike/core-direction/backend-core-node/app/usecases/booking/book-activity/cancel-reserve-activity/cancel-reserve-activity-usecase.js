const _ = require("lodash");
const BaseUseCase = require("../../../base/base-usecase");
const MemberScheduleActivityRepo = require("../../../../repositories/memberScheduleActivityRepo");
const Validator = require("../../../../entity-validations/validator");
const CancelReserveActivityEntity = require("../../../../entities/book-activity/cancel-reserve-activity/cancel-reserve-activity-entity");

module.exports = class CancelReserveActivityUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
        this.cancelReserveActivityInstance = new CancelReserveActivityEntity();
    }

    cancelReserveActivity(){
        return this.validate()
            .then(() => {
                return this.performCancelAction();
            });
    }

    validate(){
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields(){
        this.payLoad = _.pick(this.payLoad, this.cancelReserveActivityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, null);

        return this.validatorInstance.validate(
            this.cancelReserveActivityInstance.getValidationRules(),
            this.cancelReserveActivityInstance.getFieldsForUniqueness()
        );
    }

    performCancelAction(){
        return MemberScheduleActivityRepo.cancelReserveActivity(this.payLoad.user_id, this.payLoad.member_ids);
    }

}
