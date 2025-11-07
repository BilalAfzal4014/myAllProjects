const BaseRepo = require("./baseRepo");
const ActivityScheduleModel = require("../models/activityScheduleModel");
module.exports = class ActivityScheduleRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ActivityScheduleRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ActivityScheduleRepo.findByAttributeWhereIdIsNotAndGivenModel(ActivityScheduleModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchActivityScheduleAgaintClassId(class_id, dontFetchDeleted = false) {
        return ActivityScheduleRepo.findByAttributes([], [{key: "id", value: class_id}], dontFetchDeleted).first();
    }


}
