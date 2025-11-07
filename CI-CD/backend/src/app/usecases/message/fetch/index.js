const BaseUseCase = require('../../baseUsecase');
const MessageRepo = require('../../../databases/repository/message');

module.exports = class FetchMessageUseCase extends BaseUseCase {
    static fetchMessagesByGroupId(groupId) {
        return MessageRepo.getMessagesByOfAGroup(groupId);
    }
};