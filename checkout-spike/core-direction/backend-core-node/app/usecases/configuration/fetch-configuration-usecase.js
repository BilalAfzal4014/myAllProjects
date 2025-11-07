const ConfigurationsRepo = require("../../repositories/configurationsRepo");

module.exports = class FetchConfigurationUseCase {
    static fetchConfigurationsByKey(key) {
        return ConfigurationsRepo.fetchConfigurationsByKey(key)
            .then(([config]) => config);
    }
}