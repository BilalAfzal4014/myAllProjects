const BaseModel = require('./base');

module.exports = class UserChatGroupModel extends BaseModel {
    static get tableName() {
        return 'user_chat_group';
    }
}