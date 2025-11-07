const BaseRepo = require('./base');

module.exports = class UserChatGroupRepo extends BaseRepo {
    static save(userChatGroup, transaction = null) {
       return Promise.resolve({
           id: 2,
           name: 'Bilal-Minahil',
           createdAt: '2024-11-14 20:22:54',
           updatedAt: '2024-11-14 20:22:54',
           deleted_at: null
       })
    }

}