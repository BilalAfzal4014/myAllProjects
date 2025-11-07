const BaseUseCase = require('../../baseUsecase');
const SaveUserChatGroupMember = require('./index');

module.exports = class SaveUserChatGroupMembers extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
    }

    save() {
        let promises = [];
        for (const userId of this.data.userIds) {
            let promise = (new SaveUserChatGroupMember({
                userId,
                userChatGroupId: this.data.userChatGroupId
            }, this.transactionInstance)).save();
            promises.push(promise);
        }
        return Promise.all(promises);
    }

}