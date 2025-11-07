const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const CompanyDetailEntity = require("../../entities/company-detail/company-detail-entity");
const Validator = require("../../entity-validations/validator");
const ErrorTypes = require("../../errors/error-types");
const CompanyModuleConstants = require("../../constants/company-module");
const CompanyBiographyUseCase = require("../company_detail/company-biography-usecase");
const CompanyBasicInfoUseCase = require("../company_detail/company-basic-info-usecase");
const CompanyGalleryUseCase = require("../company_detail/company-gallery-usecase");
const CompanyProfileCategoriesUseCase = require("../company_detail/company-profile-categories-usecase");
const CompanyPackagesUseCase = require("../company_detail/company-packages-usecase");
const CompanyActivitiesUseCase = require("../company_detail/company-activities-usecase");
module.exports = class CompanyDetailUseCase extends BaseUseCase {
    constructor(body) {
        super();
        this.body = {};
        this.companyDetailEntityInstance = new CompanyDetailEntity();
        this.validatorInstance = null;
        this.companyDetailInteractor = null;
        this.setBody(body)
    }

    setBody(body) {
        this.body = {
            type: body.type,
            id: body.id,
            user_id: body.user_id,
            isLoggedIn: (body.user_id != 0) ? true : false,
            latitude: (body.lat) ? body.lat : null,
            longitude: (body.lng) ? body.lng : null,
            start_date: (body.start_date) ? body.start_date : null,
            end_date: (body.end_date) ? body.end_date : null,
            keyword: (body.keyword) ? body.keyword : null,
            activity_type_ids: (body.activity_type_ids) ? body.activity_type_ids : null,
            zone_ids: (body.zone_ids) ? body.zone_ids : null,
        }
    }

    fetchCompanyDetail() {
        return this.validate()
            .then(() => {
                this.assignUseCaseInteractor();
                return this.fetchDataFromDependentUseCase();
            });
    }

    validate() {
        return this.validateCompanyDetailType()
            .then((errorsList) => {
                this.sendErrorIfAnyForValidation(errorsList);
            });
    }

    validateCompanyDetailType() {
        const payload = _.pick({
            type: this.body.type,
            id: this.body.id
        }, this.companyDetailEntityInstance.getUserProvidedFields());
        this.validatorInstance = new Validator(this.body, FosUserUserRepo, false);
        return this.validatorInstance.validate(
            this.companyDetailEntityInstance.getValidationRules(),
            this.companyDetailEntityInstance.getFieldsForUniqueness()
        );
    }

    assignUseCaseInteractor() {
        this.companyDetailInteractor = this.getRequiredCompanyDetailUseCaseInteractor();
    }

    getRequiredCompanyDetailUseCaseInteractor() {
        switch (this.body.type) {
            case CompanyModuleConstants.BIOGRAPHY:
                return new CompanyBiographyUseCase(this.body.id);
            case CompanyModuleConstants.BASIC_INFO:
                return new CompanyBasicInfoUseCase(this.body.id);
            case CompanyModuleConstants.GALLERY:
                return new CompanyGalleryUseCase(this.body.id);
            case CompanyModuleConstants.PROFILE_CATEGORIES:
                return new CompanyProfileCategoriesUseCase(this.body.id);
            case CompanyModuleConstants.PACKAGES:
                return new CompanyPackagesUseCase(this.body);
            case CompanyModuleConstants.ACTIVITIES:
                return new CompanyActivitiesUseCase(this.body);
                Default : return this.sendErrorNotfound();

        }
    }

    fetchDataFromDependentUseCase() {
        return this.companyDetailInteractor.fetchData();
    }

    sendErrorIfAnyForValidation(errorList) {
        this.handleErrorIfExist(errorList,
            ErrorTypes.NOT_FOUND,
            "Company Detail Validation Failed",
            "BusinessError from validate function in Company Detail for Company Detail"
        );
    }

    sendErrorNotfound() {
        return this.handleErrorIfExist([
                {
                    "field": "",
                    "error": ""
                }
            ],
            ErrorTypes.NOT_FOUND,
            "",
            ""
        );
    }

}
