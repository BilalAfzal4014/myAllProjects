const BaseRepo = require("./baseRepo");
const FosUserUserGroupModel = require("../models/fosUserUserGroupModel");

module.exports = class FosUserUserGroupRepo extends BaseRepo {

    static findUserBelongsToParticularGroup(email, groupCode) {
        return FosUserUserGroupModel.findUserBelongsToParticularGroup(email, groupCode);
    }

    static assignUserParticularGroup(data,transactionInstance){

        return FosUserUserGroupRepo.save(data,transactionInstance);
    }

    static save(userData, transaction = null) {

        return FosUserUserGroupModel.query(transaction).insert(userData);
    }
}