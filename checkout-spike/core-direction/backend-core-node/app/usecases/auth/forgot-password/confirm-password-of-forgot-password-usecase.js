const _ = require("lodash");
const BaseUseCase = require("../../base/base-usecase");
const ConfirmLinkOfForgotPasswordUseCase = require("../../auth/forgot-password/confirm-link-of-forgot-password-usecase");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const BcryptHelper = require("../../../helpers/bcrypt-helper");
const ChainedDataHelper = require("../../../helpers/chained-data-helper");
const ConfirmPasswordOfForgotPasswordEntity = require("../../../entities/forgot-password/confirm-password-of-forgot-password-entity");
const Validator = require("../../../entity-validations/validator");
const ErrorTypes = require("../../../errors/error-types");

module.exports = class ConfirmPasswordOfForgotPasswordUseCase extends BaseUseCase {
    constructor(confirmationLinkToken, payLoad) {
        super();
        this.confirmationLinkToken = confirmationLinkToken;
        this.confirmPasswordInteractor = new ConfirmPasswordOfForgotPasswordEntity();
        this.payLoad = payLoad;
        this.email = null;
        this.confirmLinkOfForgotPasswordUseCaseInteractor = new ConfirmLinkOfForgotPasswordUseCase(confirmationLinkToken);
    }

    savePassword() {
        return this.validate()
            .then(() => {
                return this.updatePasswordToBcryptIt();
            }).then((hashedPassword) => {
                return this.setPassword(hashedPassword);
            }).then(() => {
                return this.setEmail(this.confirmLinkOfForgotPasswordUseCaseInteractor.Email);
            }).then(() => {
                return this.storePasswordInDataBase();
            });
    }


    validate() {
        return Promise.all([
            this.validateConfirmationLinkToken(),
            this.validateUserProvidedFields()
        ]).then((errorsList) => {
            errorsList = ChainedDataHelper.convertNestedToFlatArray(errorsList);
            this.sendErrorsForValidations(errorsList)
        });
    }

    validateConfirmationLinkToken() {
        return this.confirmLinkOfForgotPasswordUseCaseInteractor.validateWithoutThrowingErrors();
    }

    validateUserProvidedFields() {
        this.payLoad = _.pick(this.payLoad, this.confirmPasswordInteractor.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, FosUserUserRepo);

        return this.validatorInstance.validate(
            this.confirmPasswordInteractor.getValidationRules(),
            this.confirmPasswordInteractor.getFieldsForUniqueness()
        );
    }

    updatePasswordToBcryptIt() {
        return BcryptHelper.convertStrIntoBcryptStr(this.payLoad.password);
    }

    storePasswordInDataBase() {
        return FosUserUserRepo.updatePasswordByEmail(this.email, this.payLoad.password);
    }

    setEmail(email) {
        this.email = email;
    }

    setPassword(password) {
        this.payLoad.password = password;
    }

    sendErrorsForValidations(errorsList) {
        this.handleErrorIfExist(
            errorsList,
            ErrorTypes.NOT_FOUND,
            "Confirm password of forgot password validation failed",
            "BusinessError from validation function in ConfirmPasswordOfForgotPasswordUseCase"
        );
    }
}