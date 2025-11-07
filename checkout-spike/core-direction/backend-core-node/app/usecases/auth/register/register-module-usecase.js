const _ = require("lodash");
const BaseUseCase = require("../../base/base-usecase");
const FosUserUserRepo = require("../../../repositories/fosUserUserRepo");
const MemberKeyRepo = require("../../../repositories/memberKeyRepo");
const RegisterModuleEntity = require("../../../entities/register-module/register-module-entity");
const CorporateKeyRepo = require("../../../repositories/corporateKeyRepo");
const ErrorTypes = require("../../../errors/error-types");
const Validator = require("../../../entity-validations/validator");
const ChainedDataHelper = require("../../../helpers/chained-data-helper");
const FosUserGroupConstants = require("../../../constants/fos-user-user-groups");
const BcryptHelper = require("../../../helpers/bcrypt-helper");
const GeneralHelper = require("../../../helpers/general-helper");
const {getEmailProvider} = require("../../../providers/email-providers/email-provider-factory");
const RedeemKeyUseCase = require("../../general/redeem-key/redeem-key");
const AssignGroupUseCase = require("../../general/group-assigment/assign-group");
const StorageDirectories = require('../../../constants/storage-directories');
const UserModuleUseCase = require("../../user/fetch/get-user-module-usecase");

const REGISTRATION_OPTIONS = {
    SELF: "self",
    INVITED: "invited",
};

module.exports = class RegisterModuleUseCase extends BaseUseCase {

    constructor(payload, emailInformation = {
        registrationOption: REGISTRATION_OPTIONS.SELF,
        sendEmail: true,
        invitedBy: null,
        invitedByUser: null
    }) {
        super();
        this.registerModuleEntityInstance = new RegisterModuleEntity();
        this.payLoad = payload;
        this.corporateKey = null;
        this.redeemKeyUseCase = null;
        this.AssignGroupUseCase = null;
        this.validatorInstance = null;
        this.emailInformation = emailInformation;
    }

    register() {
        return this.validate()
            .then(() => {
                return this.getTransactionInstance()
            }).then(() => {
                return this.performRegisterAction();
            }).then(() => {
                return this.commitTransaction();
            }).then(() => {
                return this.payLoad;
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    validate() {
        return this.validateUserProvidedFieldsForEmailPasswordAndCompanyKey()
            .then((errorList) => {
                errorList = ChainedDataHelper.convertNestedToFlatArray(errorList);
                this.sendErrorIfAnyForValidation(errorList);
            }).then(() => {

                return this.validateCompanyFromMemberKeyRepo()
            }).then((errorList) => {
                if (errorList && this.hasError(errorList)) {
                    errorList = [{'field': "company_key", "error": "Company key already used"}]
                    this.sendErrorIfAnyForValidation(errorList);
                }
            })
    }


    validateUserProvidedFieldsForEmailPasswordAndCompanyKey() {
        return Promise.all([
            this.validateEmailAndPasswordForRegister(),
            this.validateCompanyFromCorporateKeyRepo(),
        ]);
    }


    validateEmailAndPasswordForRegister() {
        const fetchUserProps = _.pick(this.payLoad, this.registerModuleEntityInstance.getUserProvidedFieldsForUser());
        this.validatorInstance = new Validator(fetchUserProps, FosUserUserRepo);
        return this.validatorInstance.validate(
            this.registerModuleEntityInstance.getValidationRulesForUser(),
            this.registerModuleEntityInstance.getFieldsForUniquenessForUser(),
        );
    }


    validateCompanyFromCorporateKeyRepo() {
        let CorporateKeyValidationPromise = Promise.resolve([]);
        if (this.hasCompanyKeyInPayLoad()) {
            CorporateKeyValidationPromise = this.checkCorporateKeyByQueryingModel()
                .then((key_id) => {
                    if (!key_id)
                        return [{
                            field: "company_key",
                            error: "company_key is invalid"
                        }];
                    this.redeemKeyUseCase = new RedeemKeyUseCase(key_id);
                    this.payLoad.key_id = key_id.id //for MemberKey
                    return [];
                })
        }
        return CorporateKeyValidationPromise;
    }

    hasCompanyKeyInPayLoad() {
        return this.payLoad.company_key;
    }

    checkCorporateKeyByQueryingModel() {
        return CorporateKeyRepo.validateCompanyKeyFromCorporateKey(
            this.payLoad.company_key
        );
    }

    validateCompanyFromMemberKeyRepo() {
        if (this.hasCompanyKeyInPayLoad()) {
            const memberKeyProps = _.pick(this.payLoad, this.registerModuleEntityInstance.getUserProvidedFieldsForMemberKey());
            this.validatorInstance = new Validator(memberKeyProps, MemberKeyRepo);
            return this.validatorInstance.validate(
                this.registerModuleEntityInstance.getValidationRulesForMemberKey(),
                this.registerModuleEntityInstance.getFieldsForUniquenessForMemberKey(),
            );
        }
    }


    performRegisterAction() {
        return this.performPreSaveUserActions()
            .then(() => {
                return this.saveUserInFosUserUser()
            }).then(() => {
                return this.saveUserIngredients();
            });
    }


    saveUserIngredients() {
        this.AssignGroupUseCase = new AssignGroupUseCase(
            this.payLoad.user,
            FosUserGroupConstants.MEMBER,
            this.transactionInstance
        );
        return Promise.all([
            this.AssignGroupUseCase.assignUserMemberGroup(),
            this.sendConfirmationLink(),
            this.redeemKey()
        ]);
    }

    redeemKey() {
        if (this.hasCompanyKeyInPayLoad()) {
            //return this.redeemKeyUseCase.redeemProcess(this.payLoad.user, this.transactionInstance);
            return this.redeemKeyUseCase.redeemKey(this.payLoad.user, this.transactionInstance);
        }
    }


    generateUserDataToSave() {
        return {
            firstname: this.payLoad.first_name,
            lastname: this.payLoad.last_name,
            username: this.payLoad.email.toLowerCase(),
            username_canonical: this.payLoad.email,
            email: this.payLoad.email,
            email_canonical: this.payLoad.email,
            enabled: 0,
            salt: 10,
            roles: 'a:0:{}',
            password: this.payLoad.password,
            confirmation_token: this.generateToken(),
            phone: this.payLoad.phone_number,
            is_gdpr: 1,
            date_of_birth: this.payLoad.date_of_birth,
            gender: this.payLoad.gender,
            country: this.payLoad.country,
            is_deleted: 0,
            promotional_email_enable: this.payLoad.promotional_email_enable,
            corporate_email_enable: this.payLoad.corporate_email_enable,
        }
    }

    sendConfirmationLink() {
        return this.saveConfirmationLink()
            .then((link) => {
                this.payLoad.confirmation_link = link;
                return this.sendEmailToTheUser();
            });
    }

    saveConfirmationLink() {
        let token = GeneralHelper.getUniqueId();
        return FosUserUserRepo.save({
            confirmation_token: token,
            id: this.payLoad.user
        }, this.transactionInstance).then(() => {
            return this.emailInformation.registrationOption === REGISTRATION_OPTIONS.SELF ? process.env.confirmation_link + token :
                process.env.invitation_confirmation_link + token;
        });
    }

    sendEmailToTheUser() {
        let generalPromise = Promise.resolve(true);
        if (!this.emailInformation.sendEmail)
            return generalPromise;

        if (this.emailInformation.invitedBy) {
            generalPromise = UserModuleUseCase.fetchUserById(this.emailInformation.invitedBy)
                .then((user) => this.emailInformation.invitedByUser = user)
        }

        return generalPromise
            .then(() => {
                this.emailProviderInstance = this.getEmailProvidedInstance();
                return this.emailProviderInstance.sendEmailsWithHtmlTemplate();
            });
    }

    getEmailProvidedInstance() {
        const EmailProviderClass = getEmailProvider();
        return new EmailProviderClass(
            [this.payLoad.email],
            'Confirm your registration | Core direction',
            "",
            this.emailInformation.registrationOption === REGISTRATION_OPTIONS.SELF ?
                "templates/register-user-confirmation/register-user-confirmation.ejs" : "templates/register-user-confirmation/register-invite-confirmation.ejs", {
                confirmationLink: this.payLoad.confirmation_link,
                userName: this.emailInformation.registrationOption === REGISTRATION_OPTIONS.SELF ? this.payLoad.first_name + " " + this.payLoad.last_name :
                    this.emailInformation.invitedByUser.firstname + " " + this.emailInformation.invitedByUser.lastname
            }
        );
    }

    saveUserInFosUserUser() {
        return FosUserUserRepo.save(
            this.generateUserDataToSave(),
            this.transactionInstance
        ).then((user) => {
            this.payLoad.user = user.id;
        });
    }

    performPreSaveUserActions() {
        return Promise.all([
            // this.uploadCompanyLogo(),
            this.updatePasswordToBcryptIt()
        ]);
    }

    uploadCompanyLogo() {
        return GeneralHelper.uploadBase64EncodedFile(this.payLoad.profile_image, StorageDirectories.USER_COMPANY_LOGO)
            .then((fileUrl) => {
                this.payLoad.profile_image = fileUrl;
            });
    }

    updatePasswordToBcryptIt() {
        return BcryptHelper.convertStrIntoBcryptStr(this.payLoad.password)
            .then((hashPassword) => {
                return this.payLoad.password = hashPassword;
            });
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Register  validation failed",
            "BusinessError from validate function in RegisterModuleUseCase"
        );
    }
}
