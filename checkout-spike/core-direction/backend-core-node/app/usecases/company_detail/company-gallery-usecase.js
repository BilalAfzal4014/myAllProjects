const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FacilityGalleryRepo = require("../../repositories/facilityGalleryRepo");
const ErrorTypes = require("../../errors/error-types");
module.exports = class CompanyGalleryUseCase extends BaseUseCase {
    constructor(id) {
        super();
        this.id = id;
    }

    fetchData() {
        return this.fetchCompanyGallery()
    }

    fetchCompanyGallery() {
        return FacilityGalleryRepo.findCompanyGallleryByFacilityId(this.id).then((gallery) => {

            return gallery;
        });
    }
}