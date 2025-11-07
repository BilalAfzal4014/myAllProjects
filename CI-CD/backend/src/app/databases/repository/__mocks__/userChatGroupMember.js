const BaseRepo = require('./base');
const UserChatGroupMemberModel = require('../../../../../tests/models/userChatGroupMember');

let idCounter = 3;
module.exports = class UserChatGroupMemberRepo extends BaseRepo {
    static save(userChatGroupMember, transaction = null) {
        return Promise.resolve({
            id: idCounter++,
            ...userChatGroupMember,
            createdAt: '2024-10-30 12:37:40',
            updatedAt: '2024-10-30 12:37:40',
            deletedAt: null,
        });
    }

    static findByUserChatGroupId(userChatGroupId) {
        return UserChatGroupMemberRepo.findByAttributeWhereIdIsNotAndGivenModel(UserChatGroupMemberModel, [{
            key: 'userChatGroupId',
            value: userChatGroupId
        }]);
    }
}