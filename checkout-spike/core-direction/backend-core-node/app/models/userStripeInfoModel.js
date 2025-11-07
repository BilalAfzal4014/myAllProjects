const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class UserStripeInfoModel extends BaseModel {
    static get tableName() {
        return "user_stripe_info";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;

    }

    $beforeUpdate() {
        delete this.updated_date;
    }
}