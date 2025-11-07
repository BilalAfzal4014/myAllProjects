const _ = require("lodash");
const {v4: uuid} = require("uuid");
const BaseUseCase = require("../../base/base-usecase");
const CreatJWTUseCase = require("../../jwt/create-jwt-usecase");
const SocialLoginEntity = require("../../../entities/social-login/social-login-entity");
const Validator = require("../../../entity-validations/validator");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const FosUserUserGroupRepo = require("../../../repositories/fosUserUserGroupRepo");
const RefreshTokensRepo = require("../../../repositories/refreshTokensRepo");
const ErrorTypes = require("../../../errors/error-types");
const FosUserGroupConstants = require("../../../constants/fos-user-user-groups");
const GeneralHelper = require("../../../helpers/general-helper");
const LoginModuleConstants = require("../../../constants/login-module");
const AssignGroupUseCase = require("../../general/group-assigment/assign-group");
const {getEmailProvider} = require("../../../providers/email-providers/email-provider-factory");

module.exports = class SocialLoginUseCase extends BaseUseCase {
    constructor(payLoad) {
        super();
        this.payLoad = payLoad;
        this.user = null;
        this.token = null;
        this.refreshToken = null;
        this.jwt = null;
        this.socialLoginEntityInstance = new SocialLoginEntity(this.payLoad);

    }


    loginWithoutValidation() {

        return this.performLoginAction();

    }

    performLoginAction(){

        return this.getUserByEmailOrSocialId()
            .then(() => {

                if(this.isUserNeedToVerify()){

                    return Promise.resolve({
                        message:' Please check your email to confirm'
                    })
                }else {
                    return this.performPostLoginAction();
                }
            });
    }
    getUserByEmailOrSocialId() {

        return this.getUserByEmail()
            .then(() => {

                if (!this.userFound()) {

                    return this.getUserBySocialId();
                }
            })
    }

    getUserBySocialId() {

        return this.fetchUserFromSocialId()
            .then((user)=>{
                if(!this.userFound()) {

                    if(this.isPayloadHaveEmail()) {
                        return this.createUserFromPayload();
                    }else {
                        this.handleErrorIfExist([
                                {
                                    "field": "email",
                                    "error": "Email is required"
                                }
                            ],
                            ErrorTypes.EMAIL_REQUIRED_SOCIAL,
                            "Please Provide email address to continue",
                            "BusinessError from validate function in SocialUseCase"
                        );
                    }
                }
            })

    }

    isPayloadHaveEmail(){

        return this.payLoad.email;
    }
    createUserFromPayload(){

        let userPayload = this.generateUserDataToSaveForSocial();

        return FosUserUserRepo.save(
            userPayload,
            this.transactionInstance
        ).then((user) => {
            this.user = user;
            return this.assignMemberRole(user)
                .then(()=>{
                    if(this.isUserNeedToVerify()) {
                        return this.sendConfirmationLink();
                    }else {
                        return this.user = user;
                    }
                })

        });
    }


    assignMemberRole(){

        this.AssignGroupUseCase = new AssignGroupUseCase(
            this.user.id,
            FosUserGroupConstants.MEMBER,
            this.transactionInstance
        );
        return this.AssignGroupUseCase.assignUserMemberGroup();
    }
    isUserNeedToVerify(){

        return this.payLoad.need_to_verify_email;
    }


    sendConfirmationLink() {

        return this.saveConfirmationLink(this.user)
            .then((link) => {
                this.payLoad.confirmation_link = link;
                return this.sendEmailToTheUser(link).then((response)=>{
                    console.log(response)
                });
            })
    }

    saveConfirmationLink() {
        let token = GeneralHelper.getUniqueId();
        return FosUserUserRepo.save({
            confirmation_token: token,
            id: this.user.id
        }, this.transactionInstance).then(() => {
            return process.env.confirmation_link + token;
        })


    }

    sendEmailToTheUser() {
        this.emailProviderInstance = this.getEmailProvidedInstance();
        return this.emailProviderInstance.sendEmailsWithHtmlTemplate();
    }

    getEmailProvidedInstance() {
        const EmailProviderClass = getEmailProvider();
        return new EmailProviderClass(
            [this.payLoad.email],
            'Confirm your registration | Core Direction',
            "",
            "templates/register-user-confirmation/register-user-confirmation.ejs", {
                confirmationLink: this.payLoad.confirmation_link,
                userName: " "
            }
        );
    }

    fetchUserFromSocialId(){


        let socialType = this.payLoad.type;
        switch (socialType) {
            case LoginModuleConstants.FACEBOOK:

                return FosUserUserRepo.findBySocialId(this.payLoad.id,'facebook_uid').then((user)=>{
                    return this.user = user;
                });
                break;
            case LoginModuleConstants.GOOGLE:
                return FosUserUserRepo.findBySocialId(this.payLoad.id,'gplus_uid').then((user)=>{
                    return this.user = user;
                });
                break;
            case LoginModuleConstants.APPLE:
                return FosUserUserRepo.findBySocialId(this.payLoad.id,'apple_id').then((user)=>{
                    return this.user = user;
                });
                break;

        }
    }
    getUserByEmail() {

        if (this.hasEmailInPayLoad()) {

            return this.findUserByEmailAndMemberGroup()
        } else {

            return Promise.resolve(null)
        }
    }

    userFound() {

        return this.user;
    }

    hasEmailInPayLoad() {
        return this.payLoad.email;
    }

    validate() {
        return this.validateUserProvidedFieldsForLogin()
            .then((errorList) => {
                return this.sendErrorIfAnyForValidation(errorList);
            });
    }

    validateUserProvidedFieldsForLogin() {
        this.payLoad = _.pick(this.payLoad, this.socialLoginEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.payLoad, FosUserUserRepo);

        return this.validatorInstance.validate(
            this.socialLoginEntityInstance.getValidationRules(),
            this.socialLoginEntityInstance.getFieldsForUniqueness()
        );
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Login with credentials validation failed",
            "BusinessError from validate function in LoginWithCredentialsUseCase"
        );
    }




    findUserByEmailAndMemberGroup() {
        return FosUserUserGroupRepo.findUserBelongsToParticularGroup(this.payLoad.email, FosUserGroupConstants.MEMBER)
            .then((user) => {
                if (user)
                    return this.user = user;
            });
    }


    performPostLoginAction() {
        this.generateRequiredTokens();
        return this.savePostLoginIngredients()
            .then(({jwt}) => {
                this.jwt = jwt;
                return this.commitTransaction();
            }).then(() => {
                return {jwtToken: this.jwt, refreshToken: this.refreshToken};
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    generateRequiredTokens() {
        this.token = this.generateTokenForJWT();
        this.refreshToken = this.generateRefreshToken();
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

    generateUserDataToSaveForSocial() {


        let userObjectToSave = {};
        userObjectToSave.password = this.payLoad.id;
        let socialType = this.payLoad.type;

        switch (socialType) {
            case LoginModuleConstants.FACEBOOK:

                userObjectToSave.facebook_uid = this.payLoad.id;
                break;
            case LoginModuleConstants.GOOGLE:

                userObjectToSave.gplus_uid = this.payLoad.id;
                break;
            case LoginModuleConstants.APPLE:
                userObjectToSave.apple_id = this.payLoad.id;
                break;

        }
        userObjectToSave.email = this.payLoad.email;
        userObjectToSave.email_canonical = this.payLoad.email;
        userObjectToSave.username = this.payLoad.email;
        userObjectToSave.username_canonical = this.payLoad.email;
        userObjectToSave.enabled = !this.isUserNeedToVerify();
        userObjectToSave.salt = 10;
        userObjectToSave.roles = 'a:0:{}';
        userObjectToSave.password = this.payLoad.id;
        userObjectToSave.firstname = this.payLoad.first_name || null;
        userObjectToSave.lastname = this.payLoad.last_name || null;
        userObjectToSave.is_deleted = 0;
        userObjectToSave.confirmation_token = this.generateToken();
        userObjectToSave.is_gdpr = 1;

        return userObjectToSave;
    }

}
