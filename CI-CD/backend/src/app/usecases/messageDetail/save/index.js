const BaseUseCase = require('../../baseUsecase');
const MessageDetailRepo = require('../../../databases/repository/messageDetail')

module.exports = class SaveMessageDetailUseCase extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
    }

    save() {
        return MessageDetailRepo.save({
            messageId: this.data.messageId,
            userId: this.data.userId,
            isSender: this.data.userId === this.data.sender
        }, this.transactionInstance);
    }

}