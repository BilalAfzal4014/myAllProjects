const _ = require("lodash");
const MemberBillingDetailEntity = require("../../entities/memeber-billing-detail/member-billing-detail-entity");
const Validator = require("../../entity-validations/validator");
const MemberBillingDetailRepo = require("../../repositories/memberBillingDetailRepo");
const BaseUseCase = require("../base/base-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class SaveMemberBillingDetailUseCase extends BaseUseCase {

    constructor(memberBillingDetail) {
        super();
        this.memberBillingDetail = memberBillingDetail;
        this.memberBillingDetailEntityInstance = new MemberBillingDetailEntity(memberBillingDetail);
    }

    saveMemberBillingDetail() {
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
                    "Member Billing Detail Validation Failed",
                    "BusinessError from validate function in SaveMemberBillingDetailUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.memberBillingDetail = _.pick(this.memberBillingDetail, this.memberBillingDetailEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.memberBillingDetail, MemberBillingDetailRepo);

        return this.validatorInstance.validate(
            this.memberBillingDetailEntityInstance.getValidationRules(),
            this.memberBillingDetailEntityInstance.getFieldsForUniqueness()
        );
    }

    performSaveAction() {
        return this.save();
    }

    save() {
        return MemberBillingDetailRepo.save(this.memberBillingDetail, this.transactionInstance);
    }
}