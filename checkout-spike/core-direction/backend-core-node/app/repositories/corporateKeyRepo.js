const BaseRepo = require("./baseRepo");
const CorporateKeyModel = require("../models/corporateKeyModel");

module.exports = class CorporateKeyRepo extends BaseRepo{

    static findByAttributes(attributes, extraAttributes, dontFetchDeleted = false) {
        return CorporateKeyRepo.findByAttributesAndIdIsNot(attributes, null, extraAttributes, dontFetchDeleted);
    }

    static findByAttributesAndIdIsNot(attributes, id, extraAttributes, dontFetchDeleted = false) {
        return CorporateKeyRepo.findByAttributeWhereIdIsNotAndGivenModel(CorporateKeyModel, attributes, id, extraAttributes, dontFetchDeleted);
    }

    static validateCompanyKeyFromCorporateKey(company_key) {
        return CorporateKeyModel.fetchActiveNonDefaultKeyWithCompanyKey(company_key);
    }
    static isDiscountExistForUser(member_id) {
        return CorporateKeyModel.isDiscountExistForUser(member_id);
    }
    static getKeyInfo(key) {
        return CorporateKeyModel.getKeyInfo(key);
    }

}
