const BaseRepo = require("./baseRepo");
const ScheduleDetailModel = require("../models/scheduleDetailModel");
module.exports = class ScheduleDetailRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ScheduleDetailRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ScheduleDetailRepo.findByAttributeWhereIdIsNotAndGivenModel(ScheduleDetailModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static findById = (id) => {
        return ScheduleDetailRepo.findByAttributes([], [{
            key: "id",
            value: id
        }], true).first();
    }

    static findWithActivityById(id){
        return ScheduleDetailModel.findWithActivityById(id);
    }
}
