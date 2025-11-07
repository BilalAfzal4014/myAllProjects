const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class ActivityScheduleModel extends BaseModel {
    static get tableName() {
        return "activity_schedule";
    }

}
