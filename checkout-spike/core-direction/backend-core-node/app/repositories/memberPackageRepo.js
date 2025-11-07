const BaseRepo = require("./baseRepo");
const MemberPackageModel = require("../models/memberPackageModel");

module.exports = class MemberPackageRepo extends BaseRepo {

    static save(MemberPackageData, transaction = null) {
        if (!MemberPackageData.id) {
            return MemberPackageModel.query(transaction).insertAndFetch(MemberPackageData);
        } else {
            return MemberPackageModel.query(transaction).updateAndFetchById(MemberPackageData.id, MemberPackageData);
        }
    }

    static fetchMemberPackages(payLoad) {
        return MemberPackageModel.fetchMemberPackages(payLoad);
    }

    static addInMemberPackage(payload) {
        return MemberPackageModel.addInMemberPackage(payload);
    }

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return MemberPackageRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return MemberPackageRepo.findByAttributeWhereIdIsNotAndGivenModel(MemberPackageModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static fetchSpecificPackageOfAMember(package_id, member_id) {
        return MemberPackageRepo.findByAttributes([], [{
            key: "package_id",
            value: package_id
        }, {
            key: "member_id",
            value: member_id
        }, {
            key: "status",
            value: "active"
        }], true);
    }

    static fetchSpecificPackageOfAMemberByIdWithWriteLock(id, package_id, member_id, transaction) {
        return MemberPackageModel.fetchSpecificPackageOfAMemberByIdWithWriteLock(id, package_id, member_id, transaction);
    }

    static fetchClassMemberPackages(payload) {
        return MemberPackageModel.fetchClassMemberPackages(payload);
    }

    static decCheckin(id) {
        return MemberPackageModel.decCheckin(id);
    }

    static fetchMemberPackagesByIdsAndModifiedBy(ids, modifiedBy, status) {
        return MemberPackageRepo.findByAttributes([], [{
            key: "modifiedby",
            value: modifiedBy
        }, {
            key: "status",
            value: status
        }], true).whereIn("id", ids);
    }

}
