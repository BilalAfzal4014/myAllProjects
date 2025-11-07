const _ = require("lodash");
const MemberPackageEntity = require("../../entities/memeber-package/member-package-entity");
const Validator = require("../../entity-validations/validator");
const MemberPackageRepo = require("../../repositories/memberPackageRepo");
const BaseUseCase = require("../base/base-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class SaveMemberPackageUseCase extends BaseUseCase {

    constructor(memberPackage, transaction = null) {
        super(transaction);
        this.memberPackage = memberPackage;
        this.memberPackageEntityInstance = new MemberPackageEntity(memberPackage);
    }

    saveMemberPackage() {
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
                    "Member Package Validation Failed",
                    "BusinessError from validate function in SaveMemberPackageUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.memberPackage = _.pick(this.memberPackage, this.memberPackageEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.memberPackage, MemberPackageRepo);

        return this.validatorInstance.validate(
            this.memberPackageEntityInstance.getValidationRules(),
            this.memberPackageEntityInstance.getFieldsForUniqueness()
        );
    }

    performSaveAction() {
        return this.save();
    }

    save() {
        return MemberPackageRepo.save(this.memberPackage, this.transactionInstance);
    }
}