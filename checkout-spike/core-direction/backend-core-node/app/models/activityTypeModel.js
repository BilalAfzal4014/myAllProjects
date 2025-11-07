const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");
module.exports = class ActivityTypeModel extends BaseModel {
    static get tableName() {
        return "activity_type";
    }

    static fetchActivityTypes() {

        const query = `SELECT _at.name,_at.id, _at.imageName from activity_type _at where _at.is_deleted = 0 ORDER By _at.name ASC`;
        return knex.raw(query, { }).then(([result]) => {
            return result;
        });
    }


}
