const BaseModel = require("./baseModel");

module.exports = class UserSaveCardTrackingModel extends BaseModel {
    static get tableName() {
        return "user_save_card_tracking";
    }
}