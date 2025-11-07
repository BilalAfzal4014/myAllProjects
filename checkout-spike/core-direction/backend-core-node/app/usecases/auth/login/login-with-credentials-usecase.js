const _ = require("lodash");
const {v4: uuid} = require("uuid");
const BaseUseCase = require("../../base/base-usecase");
const CreatJWTUseCase = require("../../jwt/create-jwt-usecase");
const LoginWithCredentialsEntity = require("../../../entities/login-with-credentials/login-with-credentials-entity");
const Validator = require("../../../entity-validations/validator");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const FosUserUserGroupRepo = require("../../../repositories/fosUserUserGroupRepo");
const RefreshTokensRepo = require("../../../repositories/refreshTokensRepo");
const ErrorTypes = require("../../../errors/error-types");
const FosUserGroupConstants = require("../../../constants/fos-user-user-groups");
const UserConstants = require("../../../constants/user");
const GeneralHelper = require("../../../helpers/general-helper");
const BcryptHelper = require("../../../helpers/bcrypt-helper");
const QRCode = require('qrcode')

module.exports = class LoginWithCredentialsUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
        this.loginWithCredentialsEntityInstance = new LoginWithCredentialsEntity(this.payLoad);
        this.validatorInstance = null;
        this.user = null;
        this.qr_code = null;
        this.token = null;
        this.refreshToken = null;
        this.jwt = null;
    }
    login() {
        return this.validate()
            .then(() => {
                return this.performLoginAction();
            }).then(() => {
                return this.performPostLoginAction();
            });
    }

    loginWithoutValidation() {
        return this.performLoginAction()
            .then(() => {
                return this.performPostLoginAction();
            });
    }

    validate() {
        return this.validateUserProvidedFieldsForLogin()
            .then((errorList) => {
                console.log("errors", errorList);
                return this.sendErrorIfAnyForValidation(errorList);
            }).then(() => {
                return this.validateIfUserAssigneeOfMemberGroup()
            }).then((user) => {
                console.log(user)
                if (!user)
                    return this.sendErrorIfAnyForLoginUserMemberGroup();
                if(!user.enabled)
                    return this.sendErrorIfAnyForLoginUserNotActivated();
            });
    }

    validateUserProvidedFieldsForLogin() {
        this.payLoad = _.pick(this.payLoad, this.loginWithCredentialsEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, FosUserUserRepo);

        return this.validatorInstance.validate(
            this.loginWithCredentialsEntityInstance.getValidationRules(),
            this.loginWithCredentialsEntityInstance.getFieldsForUniqueness()
        );
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Login with credentials validation failed",
            "BusinessError from validate function in LoginWithCredentialsUseCase"
        );
    }

    validateIfUserAssigneeOfMemberGroup() {
        return this.findUserByEmailAndMemberGroup();
    }

    performLoginAction() {
        return this.compareUserPassword();
    }

    findUserByEmailAndMemberGroup() {
        return FosUserUserGroupRepo.findUserBelongsToParticularGroup(this.payLoad.email.toLowerCase(), FosUserGroupConstants.MEMBER)
            .then((user) => {
                if (user)
                    return this.user = user;
            });
    }

    compareUserPassword() {
        return Promise.all([
            this.compareUserPasswordWithEnv(true),
            this.compareUserPasswordWithDatabase()
        ]).then((passwordMatchedOfEnvAndDatabase) => {
            const passwordMatched = {
                envPassword: passwordMatchedOfEnvAndDatabase[0],
                databasePassword: passwordMatchedOfEnvAndDatabase[1]
            };
            return passwordMatched.envPassword || passwordMatched.databasePassword;
        }).then((isPasswordMatched) => {
            if (!isPasswordMatched)
                this.sendErrorIfAnyForLoginPassword();
        })
    }

    compareUserPasswordWithEnv(returnPromise = false) {
        const isPasswordCompared = process.env.allow_temp_user_login === UserConstants.ACTIVE
            && process.env.temp_user_password === this.payLoad.password;

        if (returnPromise) {
            return Promise.resolve(isPasswordCompared);
        }

        return isPasswordCompared;
    }

    compareUserPasswordWithDatabase() {
        return BcryptHelper.compareFirstBcryptStrWithSecondNormalStr(this.user.password,this.payLoad.password)
    }


    performPostLoginAction() {
        return this.getTransactionInstance()
            .then(() => {
                this.generateRequiredTokens();
                return this.savePostLoginIngredients();
            }).then(({jwt}) => {
                this.jwt = jwt;
                return this.commitTransaction();
            }).then(() => {
                return {jwtToken: this.jwt, refreshToken: this.refreshToken,qrCode:this.qr_code};
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    generateRequiredTokens() {
        this.token = this.generateTokenForJWT();
        this.qr_code = this.generateQrCode();
        this.refreshToken = this.generateRefreshToken();
    }

    generateQrCode(){

        return this.generateQRImage();
    }

    generateQRImage = ()=>{

        return QRCode.toDataURL( `{user_id:${this.user.id} }` , (err, url)=> {

            this.qr_code = url;
            return url;

        })
    }
    generateTokenForJWT() {
        return this.generateToken();
    }

    generateRefreshToken() {
        return this.generateToken();
    }

    generateToken() {
        return GeneralHelper.getUniqueId();
    }

    savePostLoginIngredients() {
        return Promise.all([
            this.generateJWT(),
            this.updateTokenInFosUserUserTable(),
            this.upsertRefreshTokenInRefreshTokenTable()
        ]).then((result) => {
            return {
                jwt: result[0]
            };
        });
    }

    generateJWT() {
        return CreatJWTUseCase.createJwt({token: this.token});
    }

    updateTokenInFosUserUserTable() {
        return FosUserUserRepo.updateToken(this.user.id, this.token, this.transactionInstance);
    }

    upsertRefreshTokenInRefreshTokenTable() {
        return RefreshTokensRepo.upsert(
            this.getRefreshTokenTableData(), {
                username: this.user.username,
            }, this.transactionInstance);
    }

    getRefreshTokenTableData() {
        return {
            refresh_token: this.refreshToken,
            username: this.user.username,
            valid: GeneralHelper.getXDaysAheadDateFromCurrentDate(60)
        }
    }

    sendErrorIfAnyForLoginUserMemberGroup() {
        this.handleErrorIfExist(
            [{
                field: "",
                error: ""
            }],
            ErrorTypes.NOT_FOUND,
            "User is not the assignee of required group",
            "BusinessError from performLoginAction function in LoginWithCredentialsUseCase"
        );
    }

    sendErrorIfAnyForLoginUserNotActivated() {
        this.handleErrorIfExist(
            [{
                field: "email",
                error: ""
            }],
            ErrorTypes.NOT_FOUND,
            "User is not activated, please activate your account through email",
            "BusinessError from performLoginAction function in LoginWithCredentialsUseCase"
        );
    }

    sendErrorIfAnyForLoginPassword() {
        this.handleErrorIfExist(
            [{
                field: "password",
                error: "password is incorrect"
            }],
            ErrorTypes.NOT_FOUND,
            "Login failed due to incorrect password",
            "BusinessError from performLoginAction function in LoginWithCredentialsUseCase"
        );
    }

}
