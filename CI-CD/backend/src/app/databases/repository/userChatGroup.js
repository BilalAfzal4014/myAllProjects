const BaseRepo = require('./base');
const UserChatGroupModel = require('../models/userChatGroup');

module.exports = class UserChatGroupRepo extends BaseRepo {
    static save(userChatGroup, transaction = null) {
        if (!userChatGroup.id) {
            return UserChatGroupModel.query(transaction).insertAndFetch(userChatGroup);
        } else {
            return UserChatGroupModel.query(transaction).updateAndFetchById(userChatGroup.id, userChatGroup);
        }
    }

}