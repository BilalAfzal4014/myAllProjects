const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class ScheduleDetailModel extends BaseModel {
    static get tableName() {
        return "schedule_detail";
    }

    static findWithActivityById(scheduleDetailId){
        const query = `
            select *
            from schedule_detail join activity_schedule on activity_schedule.id = schedule_detail.schedule_id
            where schedule_detail.id = :scheduleDetailId
        `;

        return knex.raw(query, {
            scheduleDetailId
        }).then(([result]) => {
            return result[0] ? result[0] : null;
        });
    }
}
