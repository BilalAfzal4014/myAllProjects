const _ = require("lodash");
const LoginEntity = require("../../../entities/consumer/social-login-entity");
const Validator = require("../../../entity-validations/validator");
const BaseUseCase = require("../../base/base-usecase");
const ErrorTypes = require("../../../errors/error-types");
const LoginModuleUseCase = require("../../auth/login/login-module-usecase");

module.exports = class SocialLoginUseCase extends BaseUseCase {

    constructor(data) {
        super();
        this.data = data;
        this.loginEntityInstance = new LoginEntity();
        this.user = null;
    }

    login() {
        return this.validate()
            .then(() => {
                return this.authenticateFromResource();
            }).then(() => {
                return this.saveUser();
            });
    }

    validate() {
        return this.validateWithoutThrowingError()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Social login Validation Failed",
                    "BusinessError from validate function in SocialLoginUseCase"
                );
            });
    }

    validateWithoutThrowingError() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.data = _.pick(this.data, this.loginEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.data, null);

        return this.validatorInstance.validate(
            this.loginEntityInstance.getValidationRules(),
            this.loginEntityInstance.getFieldsForUniqueness()
        );
    }

    authenticateFromResource() {
        return this.getUserInfo()
            .then((user) => {
                this.user = user
            });
    };

    getUserInfo() {
        throw new Error("Abstract Implementation called");
    }

    saveUser(){
        const loginModuleUseCaseInteractor = new LoginModuleUseCase("SOCIAL", {...this.user, email: this.user.email.toLowerCase()})
        return loginModuleUseCaseInteractor.login()
    }

};
