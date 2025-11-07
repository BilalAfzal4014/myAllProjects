const BaseModel = require('./base');

module.exports = class MessageDetailModel extends BaseModel {
    static get tableName() {
        return 'message_detail';
    }
}