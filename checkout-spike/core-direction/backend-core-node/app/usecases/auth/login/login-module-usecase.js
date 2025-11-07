const _ = require("lodash");
const BaseUseCase = require("../../base/base-usecase");
const LoginWithCredentialsUseCase = require("../login/login-with-credentials-usecase");
const SocialLoginUseCase = require("../login/social-login-usecase");
const ErrorTypes = require("../../../errors/error-types");
const Validator = require("../../../entity-validations/validator");
const LoginModuleEntity = require("../../../entities/login-module/login-module-entity");
const LoginModuleConstants = require("../../../constants/login-module");
module.exports = class LoginModuleUseCase extends BaseUseCase {
    constructor(type, payLoad) {
        super();
        this.loginModuleEntityInstance = new LoginModuleEntity({type: this.type});
        this.type = type;
        this.payLoad = payLoad
        this.loginInteractor = null;
        this.validatorInstance = null;
    }

    async login() {
        return this.validate()
            .then(() => {
                return this.performLoginAction();
            });
    }

    validate() {
        return this.validateLoginType()
            .then((errorList) => {
                this.sendErrorIfAnyForValidation(errorList, "BusinessError from validateLoginType function in LoginModuleUseCase");
                this.assignUseCaseInteractor();
                return this.validatePayLoadOfDependentUseCase();
            });
    }

    validateLoginType() {
        const type = _.pick({type: this.type}, this.loginModuleEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(type, null);

        return this.validatorInstance.validate(
            this.loginModuleEntityInstance.getValidationRules(),
            this.loginModuleEntityInstance.getFieldsForUniqueness()
        );
    }

    assignUseCaseInteractor() {
        this.loginInteractor = this.getRequiredLoginUseCaseInteractor();
    }

    getRequiredLoginUseCaseInteractor() {
        switch (this.type) {
            case LoginModuleConstants.CREDENTIALS:
                return new LoginWithCredentialsUseCase(this.payLoad);
            case LoginModuleConstants.SOCIAL:
                return new SocialLoginUseCase(this.payLoad); //will write once social module will be developed
        }
    }

    validatePayLoadOfDependentUseCase() {
        return this.loginInteractor.validate();
    }

    sendErrorIfAnyForValidation(errorList, location) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Login validation failed",
            location
        );
    }

    performLoginAction() {
        return this.loginInteractor.loginWithoutValidation();
    }


}
