const BaseRepo = require('./base');

let idCounter = 1;
module.exports = class MessageDetailRepo extends BaseRepo {
    static save(messageDetail, transaction = null) {
        return Promise.resolve({
            id: idCounter++,
            ...messageDetail,
            createdAt: '2024-10-30 12:37:40',
            updatedAt: '2024-10-30 12:37:40',
            deletedAt: null
        })
    }
}