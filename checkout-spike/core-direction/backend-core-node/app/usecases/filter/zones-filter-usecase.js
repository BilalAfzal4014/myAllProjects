const _ = require("lodash");
const {v4: uuid} = require("uuid");
const BaseUseCase = require("../base/base-usecase");
const ZoneTypeRepo = require("../../repositories/zoneRepo");
const FilterModuleConstants = require("../../constants/filter-module");

module.exports = class ZoneFilterUseCase extends BaseUseCase {

    constructor(companyId) {
        super();
        this.company_id = companyId;
    }

    getData() {
        return ZoneTypeRepo.fetchZones(this.company_id).then((records) => {

            return records;
        });
    }

}
