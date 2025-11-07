const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class FosUserGroupModel extends BaseModel {
    static get tableName() {
        return "fos_user_group";
    }
}