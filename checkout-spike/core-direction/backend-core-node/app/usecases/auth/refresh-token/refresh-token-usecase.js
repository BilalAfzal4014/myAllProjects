const _ = require("lodash");
const BaseUseCase = require("../../base/base-usecase");
const ErrorTypes = require("../../../errors/error-types");
const Validator = require("../../../entity-validations/validator");
const RefreshTokenEntity = require("../../../entities/refresh-token/refresh-token-entity");
const GeneralHelper = require("../../../helpers/general-helper");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const RefreshTokensRepo = require("../../../repositories/refreshTokensRepo");

module.exports = class RefreshTokenUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
        this.refreshTokenEntityInstance = new RefreshTokenEntity();
        this.record = null;
        this.validatorInstance = null;
        this.user = null;
        this.refreshToken = null;
    }

    getJwtToken() {
        return this.validate()
            .then(() => {
                return this.fetchRefreshToken();
            }).then((record) => {
                return this.checkRefreshTokenValid();
            }).then(() => {
                return this.generateNewToken()
            }).then(() => {
                return this.performPostRefreshTokenAction()
            })
    }

    checkRefreshTokenValid() {
        if (this.isValidToken())
            return this.getUser()
        else
            return this.sendErrorIfRefreshTokenExpired();
    }
    isValidToken(){
        return GeneralHelper.checkIfSecondParamDateIsAheadOfFirst(new Date(), new Date(this.record.valid));
    }
    getUser() {
        return FosUserUserRepo.findByAttributes([], [{
            key: "username",
            value: this.record.username
        }], false).then((user) => {
            if (user.length > 0) {
                this.user = user[0];
                return user;
            } else {
                return this.sendErrorIfNoUserFound()
            }
        });
    }

    generateNewToken() {
        return this.refreshToken = GeneralHelper.getUniqueId();
    }

    fetchRefreshToken() {
        return RefreshTokensRepo.findByAttributes([], [{
            key: "refresh_token",
            value: this.payLoad.refresh_token
        }], false).then((record) => {
            this.record = record[0];
            return record;
        });
    }

    performPostRefreshTokenAction() {
        return this.getTransactionInstance()
            .then(() => {
                return this.upsertRefreshTokenInRefreshTokenTable();
            }).then(() => {
                return this.commitTransaction();
            }).then(() => {
                return {refreshToken: this.refreshToken};
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    upsertRefreshTokenInRefreshTokenTable() {
        return RefreshTokensRepo.upsert(
            this.getRefreshTokenTableData(), {
                username: this.user.username,
            }, this.transactionInstance);
    }

    validate() {
        return this.validateUserProvidedFieldsForRefreshToken()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }

    validateUserProvidedFieldsForRefreshToken() {
        this.payLoad = _.pick(this.payLoad, this.refreshTokenEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, RefreshTokensRepo, false);
        return this.validatorInstance.validate(
            this.refreshTokenEntityInstance.getValidationRules(),
            this.refreshTokenEntityInstance.getFieldsForUniqueness()
        );
    }

    getRefreshTokenTableData(result) {
        return {
            refresh_token: this.refreshToken,
            username: this.user.username,
            valid: GeneralHelper.getXDaysAheadDateFromCurrentDate(60)
        }
    }

    sendErrorIfRefreshTokenExpired() {
        this.handleErrorIfExist([{
                field: "refresh_token",
                error: "Refresh Token Expired"
            }],
            ErrorTypes.NOT_FOUND,
            "Refresh Token Expired",
            "Refresh token from validation function in RefreshTokenUseCase"
        );
    }

    sendErrorIfNoUserFound() {
        this.handleErrorIfExist([{
                field: "username",
                error: "User name not found"
            }],
            ErrorTypes.NOT_FOUND,
            "User not found",
            "Refresh token from validation function in RefreshTokenUseCase"
        );
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Refresh Token validation failed",
            "BusinessError from validate function in LoginWithCredentialsUseCase"
        );
    }

}