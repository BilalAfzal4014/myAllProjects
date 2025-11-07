const _ = require("lodash");
const {v4: uuid} = require("uuid");
const BaseUseCase = require("../../../base/base-usecase");
const BookActivityConfigurationUseCase = require("../book-activity-configuration-usecase");
const RegisterModuleUseCase = require("../../../auth/register/register-module-usecase");
const UserModuleUseCase = require("../../../user/fetch/get-user-module-usecase");
const SingleActivityEntity = require("../../../../entities/book-activity/single-activity/single-activity-entity");
const Validator = require("../../../../entity-validations/validator");
const ErrorTypes = require("../../../../errors/error-types");


module.exports = class SingleBookingUseCase extends BaseUseCase {
    constructor(user, invitedBy = null) {
        super();
        this.user = user;
        this.invitedBy = invitedBy;
        this.bookActivityConfigurationUseCaseInteractor = null;
        this.SingleActivityEntityInstance = new SingleActivityEntity();
    }

    bookActivity() {
        return this.validatePreReqs()
            .then(() => {
                return this.checkForUserExistence();
            }).then(() => {
                return this.validatePostReqs();
            }).then(() => {
                return this.performBookActivityAction();
            }).then(() => {
                return this.user;
            });
    }

    validatePreReqs() {
        return this.validatePreReqsWithoutThrowingErrors()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book single activity Validation Failed",
                    "BusinessError from validatePreReqs function in SingleBookingUseCase"
                );
            });
    }

    validatePreReqsWithoutThrowingErrors() {
        return this.validateUserProvidedFields();
    }

    validateUserProvidedFields() {
        this.user = _.pick(this.user, this.SingleActivityEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.user, null);

        return this.validatorInstance.validate(
            this.SingleActivityEntityInstance.getValidationRules(),
            this.SingleActivityEntityInstance.getFieldsForUniqueness()
        );
    }

    checkForUserExistence() {
        return this.checkIfUserExist()
            .then((exist) => {
                return !exist && this.createUser();
            }).then(() => this.user.user_id);
    }

    checkIfUserExist() {
        let userExistencePromise = null;
        if (this.user.type === "ForUnder18") {
            if (!this.user.user_id) {
                return Promise.resolve(false);
            }
            userExistencePromise = this.fetchUser("id");
        } else {
            userExistencePromise = this.fetchUser("email");
        }
        return userExistencePromise.then((exist) => !!exist);
    }

    fetchUser(onBasis) {
        const fetchUserPromise = onBasis === "id" ? this.fetchUserById() : this.fetchUserByEmail();
        return fetchUserPromise.then((user) => {
            return this.user.user_id = user && user.id ? user.id : null;
        });
    }

    fetchUserById() {
        return UserModuleUseCase.fetchUserById(this.user.user_id);
    }

    fetchUserByEmail() {
        return UserModuleUseCase.fetchUserByEmail(this.user.email);
    }

    createUser() {
        return this.registerUser()
            .then((userId) => {
                return this.user.user_id = userId;
            });
    }

    registerUser() {
        return (new RegisterModuleUseCase({
            ...this.user,
            ...(this.user.type === "ForUnder18" && {email: `${uuid()}ForUnder18_${this.user.email}`}),
            password: "gifted",
            phone_number: "N/A",
            date_of_birth: "1979-01-01",
            gender: "o",
            country: "N/A",
        }, {
            invitedBy: this.invitedBy,
            registrationOption: "invited",
            sendEmail: this.user.type !== "ForUnder18"
        })).register().then((user) => user.user);
    }

    validatePostReqs() {
        return this.validatePostReqsWithoutThrowingErrors()
            .then((errorList) => {
                this.handleErrorIfExist(
                    errorList,
                    ErrorTypes.MISSING_ATTRIBUTES,
                    "Book single activity Validation Failed",
                    "BusinessError from validatePostReqs function in SingleBookingUseCase"
                );
            });
    }

    validatePostReqsWithoutThrowingErrors() {
        return this.validateBookActivityConfiguration();
    }

    validateBookActivityConfiguration() {
        this.initializeBookActivityConfigurationUseCase();
        return this.bookActivityConfigurationUseCaseInteractor.validateWithoutThrowingError()
            .then((errorList) => {
                return errorList.length ? errorList : this.initializeRequiredBookActivityUseCase();
            }).then((errorList) => {
                return Array.isArray(errorList) && errorList.length ? errorList : this.validateFromRequiredBookingInteractor();
            });
    }

    initializeBookActivityConfigurationUseCase() {
        this.bookActivityConfigurationUseCaseInteractor = new BookActivityConfigurationUseCase(this.user);
    }

    initializeRequiredBookActivityUseCase() {
        return this.bookActivityConfigurationUseCaseInteractor.initializeRequiredBookActivityUseCase();
    }

    validateFromRequiredBookingInteractor() {
        return this.bookActivityConfigurationUseCaseInteractor.DesiredBookActivityUseCaseInteractor.validateWithoutThrowingError();
    }

    performBookActivityAction(withTransaction = true) {
        return withTransaction ? this.bookActivityConfigurationUseCaseInteractor.DesiredBookActivityUseCaseInteractor.bookActivity().then(() => this.user)
            : this.bookActivityConfigurationUseCaseInteractor.DesiredBookActivityUseCaseInteractor.bookActivityWithoutTransaction().then(() => this.user);
    }
}