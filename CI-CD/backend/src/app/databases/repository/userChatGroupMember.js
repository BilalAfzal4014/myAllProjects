const BaseRepo = require('./base');
const UserChatGroupMemberModel = require('../models/userChatGroupMember');

module.exports = class UserChatGroupMemberRepo extends BaseRepo {
    static save(userChatGroupMember, transaction = null) {
        if (!userChatGroupMember.id) {
            return UserChatGroupMemberModel.query(transaction).insertAndFetch(userChatGroupMember);
        } else {
            return UserChatGroupMemberModel.query(transaction).updateAndFetchById(userChatGroupMember.id, userChatGroupMember);
        }
    }

    static findByUserChatGroupId(userChatGroupId) {
        return UserChatGroupMemberRepo.findByAttributeWhereIdIsNotAndGivenModel(UserChatGroupMemberModel, [{
            key: 'userChatGroupId',
            value: userChatGroupId
        }]);
    }
}