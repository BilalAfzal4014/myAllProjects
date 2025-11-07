const BaseUseCase = require("../../base/base-usecase");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const ErrorTypes = require("../../../errors/error-types");
const GeneralHelper = require("../../../helpers/general-helper");
const Base64Helper = require("../../../helpers/base-64-helper");

module.exports = class ConfirmLinkOfForgotPasswordUseCase extends BaseUseCase {
    constructor(token) {
        super();
        this.confirmationLinkToken = token;
        this.email = null;
    }

    get Email() {
        return this.email;
    }

    verifyConfirmationToken() {
        return this.validate()
            .then(() => {
                return this.email;
            });
    }

    validate() {
        return this.verifyTokenFromDataBase()
            .then((records) => {
                if (!records.length)
                    return this.sendErrorForTokenVerificationFromDatabase();
                return this.verifyTokenValidity()
            }).then((hasTokenLimit) => {
                if (!hasTokenLimit)
                    this.sendErrorForTokenValidity();
            });
    }

    validateWithoutThrowingErrors() {
        return this.verifyTokenFromDataBase()
            .then((records) => {
                return records.length ? this.verifyTokenValidity() : Promise.reject(this.returnErrorArrayForVerifyToken());
            }).then((hasTokenLimit) => {
                return hasTokenLimit ? [] : this.returnErrorArrayForTokenValidation();
            }).catch((error) => {
                return error instanceof Error ? Promise.reject(error) : error
            });
    }

    verifyTokenFromDataBase() {
        return FosUserUserRepo.findByConfirmationToken(this.confirmationLinkToken);
    }

    verifyTokenValidity() {
        this.confirmationLinkToken = this.getDecodedLink();
        const [email, date] = this.getEmailDateAndOtherFieldsIfAnyFromToken();
        this.email = email;
        return this.verifyIfDateIsNotPassed(date);
    }

    getDecodedLink() {
        return Base64Helper.base64Decode(this.confirmationLinkToken);
    }

    getEmailDateAndOtherFieldsIfAnyFromToken() {
        const tokenFields = this.confirmationLinkToken.split(";;");
        return [tokenFields[0], tokenFields[2]];
    }

    verifyIfDateIsNotPassed(date) {
        const hasTokenLimit = GeneralHelper.checkIfSecondParamDateIsAheadOfFirst(new Date(), new Date(date));
        return hasTokenLimit;
    }

    sendErrorForTokenVerificationFromDatabase() {
        this.handleErrorIfExist(
            this.returnErrorArrayForVerifyToken(),
            ErrorTypes.NOT_FOUND,
            "Confirmation link validation failed",
            "BusinessError from validation function in ConfirmForgotPasswordLinkUseCase"
        );
    }

    returnErrorArrayForVerifyToken() {
        return [{
            field: "confirmationLinkToken",
            error: "ConfirmationLink token doesn't exist"
        }];
    }

    sendErrorForTokenValidity() {
        this.handleErrorIfExist(
            this.returnErrorArrayForTokenValidation(),
            ErrorTypes.NOT_FOUND,
            "Confirmation link validation failed",
            "BusinessError from validation function in ConfirmForgotPasswordLinkUseCase"
        );
    }

    returnErrorArrayForTokenValidation() {
        return [{
            field: "confirmationLinkToken",
            error: "Token Expired"
        }];
    }
}