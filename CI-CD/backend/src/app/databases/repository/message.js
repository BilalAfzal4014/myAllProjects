const BaseRepo = require('./base');
const MessageModel = require('../models/message');

module.exports = class MessageRepo extends BaseRepo {
    static save(message, transaction = null) {
        if (!message.id) {
            return MessageModel.query(transaction).insertAndFetch(message);
        } else {
            return MessageModel.query(transaction).updateAndFetchById(message.id, message);
        }
    }

    static getMessagesByOfAGroup(groupId){
        return MessageModel.getMessagesByOfAGroup(groupId);
    }
}