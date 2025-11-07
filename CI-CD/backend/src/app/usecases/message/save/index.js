const BaseUseCase = require('../../baseUsecase');
const MessageRepo = require('../../../databases/repository/message');

module.exports = class SaveMessageUseCase extends BaseUseCase {
    constructor(data, transaction = null) {
        super(transaction);
        this.data = data;
    }

    save() {
        return MessageRepo.save(this.data, this.transactionInstance);
    }

}