const BaseRepo = require("./baseRepo");
const FacilityGalleryModel = require("../models/facilityGalleryModel");

module.exports = class FacilityGalleryRepo extends BaseRepo {

    static findByAttributesWithOrderBy(attributes, extraAttributes, dontFetchDeleted = true) {
        return FacilityGalleryRepo.findByAttributesWithOrderByAndGivenModel(FacilityGalleryModel, attributes, extraAttributes, dontFetchDeleted,true);
    }

    static findCompanyGallleryByFacilityId(id) {
        return FacilityGalleryRepo.findByAttributesWithOrderBy([], [{key: 'facility_id', value: id},{key: 'is_deleted', value: 0}], {
            key: 'sequence',
            order_by: 'ASC'
        },false);
    }
}