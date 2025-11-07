const BaseUseCase = require('../../baseUsecase');
const UserChatGroupMemberRepo = require('../../../databases/repository/userChatGroupMember');

module.exports = class FetchUserChatGroupMemberUseCase extends BaseUseCase {
    static fetchUserChatGroupId(userChatGroupId) {
        return UserChatGroupMemberRepo.findByUserChatGroupId(userChatGroupId);
    }
};