const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class RefreshTokensModel extends BaseModel {
    static get tableName() {
        return "refresh_tokens";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;
        delete this.is_deleted;
    }

    $beforeUpdate() {
        delete this.updated_date;
        delete this.is_deleted;
    }

}