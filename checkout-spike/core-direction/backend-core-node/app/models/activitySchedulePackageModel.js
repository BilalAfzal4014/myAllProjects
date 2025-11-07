const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class ActivitySchedulePackageModel extends BaseModel {
    static get tableName() {
        return "activity_schedule_package";
    }


    static fetchUserPackage(package_id) {

        const query = `select *
 from activity_schedule_package acp 
inner join member_package mp on acp.package_id = mp.package_id
where mp.is_deleted=0 and mp.status='active' and acp.package_id=:package_id`;

        return knex.raw(query, {
            package_id,
        }).then(([result]) => {
            return result;
        });
    }
}
