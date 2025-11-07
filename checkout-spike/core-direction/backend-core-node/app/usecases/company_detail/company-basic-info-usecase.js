const _ = require("lodash");
const BaseUseCase = require("../base/base-usecase");
const FosUserUserRepo = require("../../repositories/fosUserUserRepo");
const ErrorTypes = require("../../errors/error-types");
const CompanyDetailEntity = require("../../entities/company-detail/company-detail-entity");
module.exports = class CompanyBiographyUseCase extends BaseUseCase {
    constructor(id) {
        super();
        this.id = id;
        this.user = null;
        this.companyDetailEntityInstance = new CompanyDetailEntity();
    }

    fetchData() {
        return this.fetchCompanyBasicInfo()
    }

    fetchCompanyBasicInfo() {
        return FosUserUserRepo.findByAttributes([], [{
            key: "id",
            value: this.id
        }], false).then((user) => {
            this.user = user[0]
            return this.returnSelectedUserData();
        });
    }

    returnSelectedUserData() {
        return _.pick(this.user, this.companyDetailEntityInstance.getUserFieldsForBasicInfo());
    }
}