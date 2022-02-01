const BaseRepo = require("./baseRepo");
const CourseModel = require("../models/courseModel");

module.exports = class CourseRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return CourseRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return CourseRepo.findByAttributeWhereIdIsNotAndGivenModel(CourseModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static findById(id) {
        return CourseRepo.findByAttributes([], [{
            key: "id",
            value: id
        }], true).first();
    }

    static findAll() {
        return CourseRepo.findByAttributes([], [], true);
    }

}
