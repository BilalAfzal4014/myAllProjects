const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class WhiteLabelSettingModel extends BaseModel {

    static get tableName() {
        return "white_label_setting";
    }

    static getWhiteLabelSetting(apiKey_id) {
        const query = `SELECT white_label_setting.*
                        FROM BookingWhiteLabel 
                        right JOIN white_label_setting ON BookingWhiteLabel.whiteLabelSetting_id = white_label_setting.id 
                        where BookingWhiteLabel.apiKey_id = :apiKey_id`;

        return knex.raw(query, {apiKey_id}).then(([result]) => {
            return result;
        });
    }


}