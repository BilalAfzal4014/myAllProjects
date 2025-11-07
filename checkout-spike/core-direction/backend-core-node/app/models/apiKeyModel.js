const BaseModel = require("./baseModel");

module.exports = class ApiKeyModel extends BaseModel {
    static get tableName() {
        return "api_key";
    }
}