const BaseRepo = require("./baseRepo");
const UserModel = require("../models/userModel");

module.exports = class UserRepo extends BaseRepo {

    static save(user, transaction = null) {
        if (!user.id) {
            return UserModel.query(transaction).insertAndFetch(user);
        } else {
            return UserModel.query(transaction).updateAndFetchById(user.id, user);
        }
    }

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return UserRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return UserRepo.findByAttributeWhereIdIsNotAndGivenModel(UserModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static findAll() {
        return UserRepo.findByAttributes([], [], true);
    }

    static findById(id) {
        return UserRepo.findByAttributes([], [{
            "key": "id",
            value: id
        }], true).first();
    }
}
