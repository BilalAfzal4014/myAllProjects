const BaseUseCase = require('../../baseUsecase');
const UserChatGroupRepo = require('../../../databases/repository/userChatGroup');

module.exports = class SaveUserChatGroup extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
    }

    save() {
        return UserChatGroupRepo.save(this.data, this.transactionInstance);
    }
}