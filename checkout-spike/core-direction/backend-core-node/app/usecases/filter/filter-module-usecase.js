const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FilterModuleConstants = require("../../constants/filter-module");
const FilterEntity = require("../../entities/filter/filter-entity");
const ProfileCategoryUseCase = require("./profile-category-usecase");
const ActivityFilterUseCase = require("./activity-filter-usecase");
const ZoneFilterUseCase = require("./zones-filter-usecase");
const Validator = require("../../entity-validations/validator");
const ErrorTypes = require("../../errors/error-types");
module.exports = class FilterModuleUseCase extends BaseUseCase {
    constructor(type,id) {
        super();
        this.type = type;
        this.id = id;
        this.filterEntityInstance = new FilterEntity();
        this.validatorInstance = null;
        this.filterInteractor = null;
    }

    fetchData() {
        return this.validate()
            .then(() => {
                return this.assignUseCaseInteractor();
            })
            .then(() => {
                return this.performFilterAction()
            }).catch((error) => {
                throw error;
            });
    }

    validate() {
        return this.validateFilterType()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }

    assignUseCaseInteractor() {
        this.filterInteractor = this.getRequiredFilterUseCaseInteractor();
    }

    getRequiredFilterUseCaseInteractor() {
        switch (this.type) {
            case FilterModuleConstants.PROFILE_CATEGORY:
                return new ProfileCategoryUseCase();
            case FilterModuleConstants.ACTIVITY_TYPE:
                return new ActivityFilterUseCase();
            case FilterModuleConstants.ZONE:
                return new ZoneFilterUseCase(this.id);
        }
    }

    validateFilterType() {
        const type = _.pick({type: this.type}, this.filterEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(type, null);
        return this.validatorInstance.validate(
            this.filterEntityInstance.getValidationRules(),
            this.filterEntityInstance.getFieldsForUniqueness()
        );
    }

    performFilterAction() {
        return this.filterInteractor.getData();
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            FilterModuleConstants.TYPE_INCORRECT,
            "BusinessError from validate function in Filter for profile category"
        );
    }
}
