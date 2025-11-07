const BaseRepo = require("./baseRepo");
const ZoneModel = require("../models/zoneModel");
module.exports = class ZoneRepo extends BaseRepo {




    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ZoneRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ZoneRepo.findByAttributeWhereIdIsNotAndGivenModel(ZoneModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchZones(id) {
        return ZoneModel.query()
            .select('id','title','description')
            .where({
                'modifiedBy':id,
                'is_deleted': 0
            });

    }

    static findById = (id)=>{

        return ZoneRepo.findByAttributes([], [{
            key: "id",
            value: id
        }], true).first();
    }
}