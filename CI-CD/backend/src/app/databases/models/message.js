const BaseModel = require('./base');
const {knex} = require("../sql-connection");

module.exports = class MessageModel extends BaseModel {
    static get tableName() {
        return 'message';
    }

    static getMessagesByOfAGroup(groupId) {
        const query = `
            select message.id as message_id, message.text, message_detail.user_id as sender_id, user.name
            from message join message_detail on message.id = message_detail.message_id
            join user on user.id = message_detail.user_id
            where message.user_chat_group_id = :groupId and message_detail.is_sender = 1
            order by message.created_at
        `;

        return knex.raw(query, {
            groupId,
        }).then(([result]) => {
            return result;
        });
    }
}