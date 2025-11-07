const BaseUseCase = require("../base/base-usecase");
const UserEmergencyEntity = require("../../entities/user-module/user-emergency-entity");
const _ = require("lodash");
const Validator = require("../../entity-validations/validator");
const UserEmergencyRepo = require("../../repositories/useremergencyRepo");
const ErrorTypes = require("../../errors/error-types");

module.exports = class SaveUserEmergencyUseCase extends BaseUseCase {
    constructor(userEmergency, transaction = null) {
        super(transaction);
        this.userEmergency = userEmergency;
        this.userEmergencyEntityInstance = new UserEmergencyEntity();
    }

    set Transaction(transaction) {
        this.transactionInstance = transaction;
    }

    save() {
        this.validate()
            .then(() => {
                return this.performSaveAction();
            })
    }

    validate() {
        return this.validateWithOutThrowingErrors()
            .then((errorList) => {
                this.handleErrorOfValidationForUserEmergency(errorList);
            });
    }

    validateWithOutThrowingErrors() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.userEmergency = _.pick(this.userEmergency, this.userEmergencyEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.userEmergency, UserEmergencyRepo);

        return this.validatorInstance.validate(
            this.userEmergencyEntityInstance.getValidationRules(),
            this.userEmergencyEntityInstance.getFieldsForUniqueness()
        );
    }

    handleErrorOfValidationForUserEmergency(errorList) {
        this.handleErrorIfExist(
            errorList,
            ErrorTypes.MISSING_ATTRIBUTES,
            "Update user emergency Module Validation Failed",
            "BusinessError from validate function in SaveUserEmergencyUseCase"
        );
    }

    performSaveAction() {
        return this.saveUserEmergency();
    }

    saveUserEmergency() {
        return UserEmergencyRepo.upsert(this.getTrimmedUserEmergency(), {
            user_id: this.userEmergency.user_id
        }, this.transactionInstance);
    }

    getTrimmedUserEmergency() {
        return {
            is_deleted: 0,
            ...this.userEmergency
        }
    }
}