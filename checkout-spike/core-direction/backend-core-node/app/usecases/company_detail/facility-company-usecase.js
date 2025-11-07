const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const ErrorTypes = require("../../errors/error-types");
module.exports = class FacilityCompanyUseCase extends BaseUseCase {
    constructor() {
        super();
    }

    fetchData() {
        return FosUserUserRepo.getFacilityCompanies().then((companies) => {
            return companies;
        });
    }
}
