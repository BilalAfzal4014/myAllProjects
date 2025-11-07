const BaseRepo = require('./base');
const UserModel = require('../../../../../tests/models/UserModel');

module.exports = class UserRepo extends BaseRepo {
    static findUserById(Id) {
        return UserRepo.findByAttributeWhereIdIsNotAndGivenModel(UserModel, [{
            key: 'id',
            value: Id
        }]).then((users) => {
            const [user] = users;
            return user;
        });
    }

    static findAllUsersExceptCurrent(Id) {
        return Promise.resolve([]);
    }
}