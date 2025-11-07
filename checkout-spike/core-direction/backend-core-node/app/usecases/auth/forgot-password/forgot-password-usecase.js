const _ = require("lodash");
const BaseUseCase = require("../../base/base-usecase");
const ErrorTypes = require("../../../errors/error-types");
const Validator = require("../../../entity-validations/validator");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const ForgotPasswordEntity = require("../../../entities/forgot-password/forgot-password-entity");
const {getEmailProvider} = require("../../../providers/email-providers/email-provider-factory");
const ForgotPasswordConstants = require("../../../constants/forgot-password");
const GeneralHelper = require("../../../helpers/general-helper");

module.exports = class ForgotPasswordUseCase extends BaseUseCase {
    constructor(payLoad, url) {
        super();
        this.payLoad = payLoad;
        this.url = url;
        this.forgotPasswordEntityInstance = new ForgotPasswordEntity();
        this.emailProviderInstance = null;
        this.linkToRecoverPassword = null;
        this.tokenOfLink = null;

    }

    recoverPassword() {
        return this.validate()
            .then(() => {
                return this.generateLink();
            }).then(() => {
                return this.performPostGenerateLinkAction();
            });
    }

    validate() {
        return this.validateUserProvidedFieldsForForgotPassword()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }

    validateUserProvidedFieldsForForgotPassword() {
        this.payLoad = _.pick(this.payLoad, this.forgotPasswordEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, FosUserUserRepo);

        return this.validatorInstance.validate(
            this.forgotPasswordEntityInstance.getValidationRules(),
            this.forgotPasswordEntityInstance.getFieldsForUniqueness()
        );
    }

    generateLink() {
        this.tokenOfLink = GeneralHelper.makeBase64StringByConcatAllELEOfArrWithGivenDelimiter(
            [this.payLoad.email, GeneralHelper.getUniqueId(), GeneralHelper.addXMinutesToCurrentDateTime(parseInt(process.env.forgot_password_link_expiry_time))],
            ";;"
        );
        this.linkToRecoverPassword = process.env.forget_password_link + this.tokenOfLink;
    }

    performPostGenerateLinkAction() {
        return Promise.all([this.sendEmailToTheUser(), this.saveLinkInFosUserUserRepo()])
            .then((response) => {
                return {
                    message: 'password reset successful'
                }
            });
    }

    saveLinkInFosUserUserRepo() {
        return FosUserUserRepo.updateMultipleFieldsByEmail(this.payLoad.email, {confirmation_token: this.tokenOfLink, password_requested_at: new Date()})
    }

    sendEmailToTheUser() {
        this.emailProviderInstance = this.getEmailProvidedInstance();
        return this.emailProviderInstance.sendEmailsWithHtmlTemplate();
    }

    getEmailProvidedInstance() {
        const EmailProviderClass = getEmailProvider();
        return new EmailProviderClass(
            [this.payLoad.email],
            ForgotPasswordConstants.EMAIL_SUBJECT,
            "",
            "templates/forgot-password/forgot-password-template.ejs", {
                confirmationLink: this.linkToRecoverPassword
            }
        );
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Forgot password validation failed",
            "BusinessError from validation function in ForgotPasswordUseCase"
        );
    }

}