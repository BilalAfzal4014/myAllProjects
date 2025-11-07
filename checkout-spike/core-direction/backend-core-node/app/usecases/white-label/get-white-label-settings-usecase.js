const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const WhiteLabelSettingRepo = require("../../repositories/whiteLabelSettingRepo");
const ApiKeyRepo = require("../../repositories/apiKeyRepo");
const ErrorTypes = require("../../errors/error-types");

module.exports = class WhiteLabelSettingUseCase extends BaseUseCase {
    constructor(payload) {
        super();
        this.payload = payload;
    }

    fetchWhiteLabelSetting() {
        return this.fetchApiKeyRecord().then((api_key_id) => {
            return this.getWhiteLabelSetting(api_key_id);
        });
    }

    fetchApiKeyRecord() {
        return ApiKeyRepo.findByApiKeyAndPassword(this.payload.username, this.payload.password).then((record) => {
            return record[0].id;
        })
    }

    getWhiteLabelSetting(id) {
        return WhiteLabelSettingRepo.fetchWhiteLabelSetting(id).then((setting) => {
            if (setting.length > 0)
                return setting[0];
            else
                return this.sendErrorSettingNotfound();
        })
    }

    sendErrorSettingNotfound() {
        return this.handleErrorIfExist([
                {
                    "field": "",
                    "error": ""
                }
            ],
            ErrorTypes.NOT_FOUND,
            "",
            ""
        );
    }
}