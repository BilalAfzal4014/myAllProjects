const BaseModel = require("./baseModel");

module.exports = class MemberBillingDetailModel extends BaseModel {
    static get tableName() {
        return "member_billing_detail";
    }
}