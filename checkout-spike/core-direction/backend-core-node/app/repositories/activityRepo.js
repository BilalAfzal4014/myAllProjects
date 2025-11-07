const BaseRepo = require("./baseRepo");
const ActivityModel = require("../models/activityModel");

module.exports = class ActivityRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ActivityRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ActivityRepo.findByAttributeWhereIdIsNotAndGivenModel(ActivityModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchCompanyActivities(body) {
        return ActivityModel.fetchCompanyActivities(body)
    }

    static findById = (id)=>{

        return ActivityRepo.findByAttributes([], [{
            key: "id",
            value: id
        }], true).first();
    }
}
