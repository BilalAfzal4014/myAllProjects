const BaseUseCase = require('../../baseUsecase');
const SaveUserChatGroup = require('../save');
const SaveUserChatGroupMembers = require('../../userChatGroupMember/save/saveMultiple');
const FetchUserUseCase = require('../../user/fetch');

module.exports = class CreateUserChatGroup extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
        this.savedUserChatGroup = null;
        this.savedUserChatGroupMember = null;
        this.users = null;
    }

    create() {
        return this.handleCreateScenario();
    }

    handleCreateScenario() {
        return this.saveUserChatGroup()
            .then(() => {
                return this.saveUserChatGroupMember();
            }).then(() => {
                return {
                    chatGroup: this.savedUserChatGroup,
                    chatGroupMember: this.savedUserChatGroupMember,
                    users: this.users
                }
            });
    }


    saveUserChatGroup() {
        return this.checkAndGenerateGroupNameIfDontExist()
            .then((groupName) => {
                return (new SaveUserChatGroup({name: groupName}, this.transactionInstance)).save();
            }).then((userChatGroup) => {
                this.savedUserChatGroup = userChatGroup;
                return this.savedUserChatGroup;
            });
    }

    checkAndGenerateGroupNameIfDontExist() {
        let promises = [];
        if (!this.data.groupName) {
            const userIds = [this.data.userId, ...this.data.userIds];
            for (const userId of userIds)
                promises.push(FetchUserUseCase.fetchById(userId));
            return Promise.all(promises)
                .then((users) => {
                    this.users = users;
                    return this.generateGroupName(users)
                })
        }
        return Promise.resolve(this.data.groupName);
    }

    generateGroupName(users) {
        let str = '';
        let index = 0;
        for (const user of users) {
            str += user.name;
            if (index !== users.length - 1) str += '-';
            index++;
        }
        return str;
    }

    saveUserChatGroupMember() {
        return (new SaveUserChatGroupMembers({
            userIds: [this.data.userId, ...this.data.userIds],
            userChatGroupId: this.savedUserChatGroup.id
        }, this.transactionInstance)).save()
            .then((userChatGroupMember) => {
                this.savedUserChatGroupMember = userChatGroupMember;
                return this.savedUserChatGroupMember;
            });
    }
}