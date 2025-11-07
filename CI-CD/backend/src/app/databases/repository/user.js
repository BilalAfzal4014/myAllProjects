const BaseRepo = require('./base');
const UserModel = require('../models/user');

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
        return UserModel.getAllUsersListingWithRelevantGroupExceptTheCurrentOne(Id);
    }
}