const BaseRepo = require("./baseRepo");
const FosUserUserGroupModel = require("../models/fosUserGroupModel");

module.exports = class FosUserGroupRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return FosUserGroupRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return FosUserGroupRepo.findByAttributeWhereIdIsNotAndGivenModel(FosUserUserGroupModel, attributes, id, extraAttributes, dontFetchDeleted);
    }


    static findByGroupCode(code) {
        return FosUserGroupRepo.findByAttributes([], [{
            key: "code",
            value: code
        }], false);
    }

}