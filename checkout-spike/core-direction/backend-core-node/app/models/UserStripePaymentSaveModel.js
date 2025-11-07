const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class UserStripePaymentSaveModel extends BaseModel {
    static get tableName() {
        return "user_package";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;

    }

    $beforeUpdate() {
        delete this.updated_date;
    }
}