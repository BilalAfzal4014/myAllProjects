const BaseModel = require("./baseModel");

module.exports = class ConfigurationsModel extends BaseModel{
    static get tableName() {
        return "configurations";
    }
}