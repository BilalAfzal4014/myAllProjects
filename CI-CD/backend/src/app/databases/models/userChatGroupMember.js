const BaseModel = require('./base');

module.exports = class UserChatGroupMemberModel extends BaseModel {
    static get tableName() {
        return 'user_chat_group_member';
    }
}