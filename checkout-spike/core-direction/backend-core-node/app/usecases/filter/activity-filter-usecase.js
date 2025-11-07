const _ = require("lodash");
const {v4: uuid} = require("uuid");
const BaseUseCase = require("../base/base-usecase");
const ActivityTypeRepo = require("../../repositories/activityTypeRepo");
const FilterModuleConstants = require("../../constants/filter-module");

module.exports = class ActivityFilterUseCase extends BaseUseCase {

    constructor() {
        super();
    }

    getData() {
        return ActivityTypeRepo.fetchActivityTypes().then((records) => {
            return records;
        });
    }


}
