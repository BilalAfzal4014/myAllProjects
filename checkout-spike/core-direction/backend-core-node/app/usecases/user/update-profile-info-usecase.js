const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const UpdateProfileEntity = require("../../entities/user-module/update-profile-entity");
const Validator = require("../../entity-validations/validator");
const SaveUserEmergencyUseCase = require("./save-user-emergency.usecase");
const ErrorTypes = require("../../errors/error-types");

module.exports = class UpdateProfileInfoUseCase extends BaseUseCase {
    constructor(payload, transaction = null) {
        super(transaction);
        this.payload = payload;
        this.payload.id = payload.user_id;
        this.updateProfileEntityInstance = new UpdateProfileEntity();
        this.saveUserEmergencyInteractor = null;
    }

    updateProfile() {
        return this.validate()
            .then(() => {
                return this.getTransactionInstance()
            }).then(() => {
                return this.performPreSaveAction();
            }).then(() => {
                return this.performSaveAction();
            }).then(() => {
                return this.commitTransaction()
            }).catch((error) => {
                this.rollbackTransaction();
                throw error;
            });
    }

    validate() {
        return this.validateWithOutThrowingErrors()
            .then((errorsList) => {
                this.handleErrorOfValidationForUserUpdate(errorsList);
            });
    }

    validateWithOutThrowingErrors() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        return this.validateUserProvidedFieldsForUser()
            .then((errorsList) => {
                return !errorsList.length ? this.validateUserProvidedFieldsForUserEmergency() : errorsList;
            });
    }

    validateUserProvidedFieldsForUser() {
        this.payload = _.pick(this.payload, this.updateProfileEntityInstance.getUserFieldsForUpdateProfile());
        this.validatorInstance = new Validator(this.payload, FosUserUserRepo);

        return this.validatorInstance.validate(
            this.updateProfileEntityInstance.getValidationRules(),
            this.updateProfileEntityInstance.getFieldsForUniqueness()
        );
    }

    validateUserProvidedFieldsForUserEmergency() {
        this.assignUserEmergencyUseCaseInstance();
        return this.saveUserEmergencyInteractor.validateWithOutThrowingErrors();
    }

    assignUserEmergencyUseCaseInstance() {
        this.saveUserEmergencyInteractor = new SaveUserEmergencyUseCase({
            user_id: this.payload.user_id,
            modifiedby: this.payload.user_id,
            ...this.payload.user_emergency
        });
    }

    performPreSaveAction() {
        this.initializeUserEmergencyTransactionInstance();
    }

    initializeUserEmergencyTransactionInstance() {
        this.saveUserEmergencyInteractor.Transaction = this.transactionInstance;
    }

    performSaveAction() {
        return Promise.all([
            this.saveFosUserUserGroup(),
            this.saveUserEmergency()
        ]);
    }

    saveFosUserUserGroup() {
        return FosUserUserRepo.save(this.getTrimmedUser(), this.transactionInstance);
    }

    getTrimmedUser() {
        const user = {
            ...this.payload
        }
        delete user.user_id;
        delete user.user_emergency;
        return user;
    }

    saveUserEmergency() {
        return this.saveUserEmergencyInteractor.saveUserEmergency();
    }

    handleErrorOfValidationForUserUpdate(errorList) {
        this.handleErrorIfExist(
            errorList,
            ErrorTypes.MISSING_ATTRIBUTES,
            "Update user Module Validation Failed",
            "BusinessError from validate function in UpdateProfileInfoUseCase"
        );
    }

}
