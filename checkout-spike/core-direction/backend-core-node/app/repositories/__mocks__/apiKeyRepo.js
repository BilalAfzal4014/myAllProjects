const BaseRepo = require("./baseRepo");
const ApiKeyModel = require("../../../tests/json-data-for-test-cases/models/apiKeyModel");

module.exports = class ApiKeyRepo extends BaseRepo {

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return ApiKeyRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return ApiKeyRepo.findByAttributeWhereIdIsNotAndGivenModel(ApiKeyModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static findByApiKeyAndPassword(apiKey, password) {
        return ApiKeyRepo.findByAttributes([], [{
            key: "api_key",
            value: apiKey
        }, {
            key: "api_password",
            value: password
        }], true);
    }
}