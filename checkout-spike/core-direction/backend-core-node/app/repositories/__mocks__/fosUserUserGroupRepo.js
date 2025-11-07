const BaseRepo = require("./baseRepo");
const FosUserUserModel = require("../../../tests/json-data-for-test-cases/models/fosUserUserModel")
const FosUserGroupModel = require("../../../tests/json-data-for-test-cases/models/fosUserGroupModel")
const FosUserUserGroupModel = require("../../../tests/json-data-for-test-cases/models/fosUserUserGroupModel")

module.exports = class FosUserUserGroupRepo extends BaseRepo {

    static findUserBelongsToParticularGroup(email, groupCode) {
        for (let user of FosUserUserModel) {
            if (user.email === email) {
                for (let userGroup of FosUserUserGroupModel) {
                    if (user.id === userGroup.user_id) {
                        for (let group of FosUserGroupModel) {
                            if (group.code === groupCode && userGroup.group_id === group.id)
                                return Promise.resolve(user);
                        }
                        return Promise.resolve(null);
                    }
                }
                return Promise.resolve(null);
            }
        }
        return Promise.resolve(null);
    }
}