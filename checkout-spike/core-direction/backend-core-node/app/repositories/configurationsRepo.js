const BaseRepo = require("./baseRepo");
const ConfigurationsModel = require("../models/configurationsModel");

module.exports = class ConfigurationsRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ConfigurationsRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ConfigurationsRepo.findByAttributeWhereIdIsNotAndGivenModel(ConfigurationsModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchConfigurationsByKey(key) {
        return ConfigurationsRepo.findByAttributes([], [{
            key: "key_name",
            value: key
        }, {
            key: "is_active",
            value: 1
        }], true);
    }

}
