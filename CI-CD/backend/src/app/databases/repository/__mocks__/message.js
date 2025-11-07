const BaseRepo = require('./base');

module.exports = class MessageRepo extends BaseRepo {
    static save(message, transaction = null) {
        return Promise.resolve({
            id: 1,
            ...message,
            createdAt: '2024-10-30 12:37:40',
            updatedAt: '2024-10-30 12:37:40',
            deletedAt: null
        })
    }

    static getMessagesByOfAGroup(groupId) {
        return Promise.resolve([]);
    }
}