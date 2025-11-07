const BaseRepo = require("./baseRepo");
const WhiteLabelSettingModel = require("../models/whiteLabelSettingModel");

module.exports = class WhiteLabelSettingRepo extends BaseRepo {

    static fetchWhiteLabelSetting(apiKey_id) {
        return WhiteLabelSettingModel.getWhiteLabelSetting(apiKey_id);
    }

}