const _ = require("lodash");
const SaveCardEntity = require("../../entities/save-card/save-card-entity");
const Validator = require("../../entity-validations/validator");
const UserSaveCardTrackingRepo = require("../../repositories/userSaveCardTrackingRepo");
const BaseUseCase = require("../base/base-usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class SaveUserCardDetailUseCase extends BaseUseCase {

    constructor(userCardDetail) {
        super();
        this.userCardDetail = userCardDetail;
        this.saveCardDetailEntityInstance = new SaveCardEntity(userCardDetail);
    }

    saveUserCardDetail() {
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
                    "User Card Detail Validation Failed",
                    "BusinessError from validate function in SaveUserCardDetailUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.userCardDetail = _.pick(this.userCardDetail, this.saveCardDetailEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.userCardDetail, UserSaveCardTrackingRepo);

        return this.validatorInstance.validate(
            this.saveCardDetailEntityInstance.getValidationRules(),
            this.saveCardDetailEntityInstance.getFieldsForUniqueness()
        );
    }

    performSaveAction() {
        return this.save();
    }

    save() {
        return UserSaveCardTrackingRepo.save(this.userCardDetail, this.transactionInstance);
    }
}