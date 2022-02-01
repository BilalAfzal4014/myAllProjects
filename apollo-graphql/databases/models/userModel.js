const BaseModel = require("./baseModel");
const {knex} = require("../sql-connection");

module.exports = class UserModel extends BaseModel {
    static get tableName() {
        return "user";
    }
}
