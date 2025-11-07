const _ = require("lodash");
const MemberBillingEntity = require("../../entities/memeber-billing/member-billing-entity");
const Validator = require("../../entity-validations/validator");
const MemberBillingRepo = require("../../repositories/memberBillingRepo");
const BaseUseCase = require("../base/base-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class SaveMemberBillingUseCase extends BaseUseCase {

    constructor(memberBilling) {
        super();
        this.memberBilling = memberBilling;
        this.memberBillingEntityInstance = new MemberBillingEntity(memberBilling);
    }

    saveMemberBilling() {
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
                    "Member Billing Validation Failed",
                    "BusinessError from validate function in SaveMemberBillingUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.memberBilling = _.pick(this.memberBilling, this.memberBillingEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.memberBilling, MemberBillingRepo);

        return this.validatorInstance.validate(
            this.memberBillingEntityInstance.getValidationRules(),
            this.memberBillingEntityInstance.getFieldsForUniqueness()
        );
    }

    performSaveAction() {
        return this.save();
    }

    save() {
        return MemberBillingRepo.save(this.memberBilling, this.transactionInstance);
    }
}