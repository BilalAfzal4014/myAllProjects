const BaseRepo = require('./base');
const MessageDetailModel = require('../models/messageDetail');

module.exports = class MessageDetailRepo extends BaseRepo {
    static save(messageDetail, transaction = null) {
        if (!messageDetail.id) {
            return MessageDetailModel.query(transaction).insertAndFetch(messageDetail);
        } else {
            return MessageDetailModel.query(transaction).updateAndFetchById(messageDetail.id, messageDetail);
        }
    }
}