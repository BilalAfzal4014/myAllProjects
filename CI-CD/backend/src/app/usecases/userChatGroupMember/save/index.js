const BaseUseCase = require('../../baseUsecase');
const UserChatGroupMemberRepo = require('../../../databases/repository/userChatGroupMember');

module.exports = class SaveUserChatGroupMember extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
    }

    save() {
        return UserChatGroupMemberRepo.save(this.data, this.transactionInstance);
    }

}