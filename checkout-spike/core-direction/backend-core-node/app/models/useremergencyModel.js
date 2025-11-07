const BaseModel = require("./baseModel");

module.exports = class UserEmergencyModel extends BaseModel {
    static get tableName() {
        return "UserEmergency";
    }
}