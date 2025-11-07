const BaseRepo = require("./baseRepo");
const CorporateKeyPackageModel = require("../models/corporateKeyPackageModel");

module.exports = class CorporateKeyPackageRepo extends BaseRepo {

    static getCorporatePacakges(corporate_key_id) {
        return CorporateKeyPackageModel.getCorporatePacakges(corporate_key_id);
    }

}
