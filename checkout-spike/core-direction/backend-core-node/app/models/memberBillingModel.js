const BaseModel = require("./baseModel");

module.exports = class MemberBillingModel extends BaseModel {
    static get tableName() {
        return "member_billing";
    }
}