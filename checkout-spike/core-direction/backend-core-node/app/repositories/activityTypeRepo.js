const BaseRepo = require("./baseRepo");
const ActivityTypeModel = require("../models/activityTypeModel");

module.exports = class ActivityTypeRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ActivityTypeRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ActivityTypeRepo.findByAttributeWhereIdIsNotAndGivenModel(ActivityTypeModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchActivityTypes() {
        return ActivityTypeModel.fetchActivityTypes()
    }

    static findById = (id)=>{

        return ActivityTypeRepo.findByAttributes([], [{
            key: "id",
            value: id
        }], true).first();
    }
}
