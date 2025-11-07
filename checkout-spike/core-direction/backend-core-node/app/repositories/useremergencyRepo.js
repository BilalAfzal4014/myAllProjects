const BaseRepo = require("../repositories/baseRepo");
const UserEmergencyModel = require("../models/useremergencyModel");

module.exports = class UserEmergencyRepo extends BaseRepo {
    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return UserEmergencyRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return UserEmergencyRepo.findByAttributeWhereIdIsNotAndGivenModel(UserEmergencyModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static save(userEmergency, transaction = null) {
        if (!userEmergency.id) {
            return UserEmergencyModel.query(transaction).insertAndFetch(userEmergency);
        } else {
            return UserEmergencyModel.query(transaction).updateAndFetchById(userEmergency.id, userEmergency);
        }
    }

    static upsert(userEmergency, options, transaction = null) {
        return UserEmergencyModel.query(transaction)
            .where(options)
            .then(([result]) => {
                if (result) {
                    userEmergency.id = result.id;
                } else {
                    delete userEmergency.id;
                }
                return UserEmergencyRepo.save(userEmergency, transaction);
            });
    }

    static findByUserId(userId){
        return UserEmergencyRepo.findByAttributes([], [{
            key: "user_id",
            value: userId
        }], true);
    }
}