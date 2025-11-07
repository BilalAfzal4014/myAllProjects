const BaseUseCase = require("../../base/base-usecase");
const FosUserGroupRepo = require("../../../repositories/fosUserGroupRepo");
const FosUserUserGroupRepo = require("../../../repositories/fosUserUserGroupRepo");

module.exports = class AssignGroupUseCase extends BaseUseCase {

    constructor(user,groupCode,transactionInstance) {
        super();
        this.user = user;
        this.groupCode = groupCode;
        this.transactionInstance = transactionInstance;
    }

    assignUserMemberGroup() {
        return this.getGroupData()
            .then((groupData) => {
                if (groupData) {
                    return this.addUserToGroup(groupData)
                }

            })
    }

    getGroupData() {

        return FosUserGroupRepo.findByGroupCode(this.groupCode).first();
    }

    addUserToGroup(groupData) {

        return FosUserUserGroupRepo.assignUserParticularGroup({
            user_id: this.user,
            group_id: groupData.id
        }, this.transactionInstance)
    }
}