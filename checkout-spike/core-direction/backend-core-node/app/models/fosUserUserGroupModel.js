const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class FosUserUserGroupModel extends BaseModel {
    static get tableName() {
        return "fos_user_user_group";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;
    }

    $beforeUpdate() {
        delete this.updated_date;
    }
    static findUserBelongsToParticularGroup(email, groupCode) {
        const query = `
            select fos_user_user.*
            from fos_user_user
                     left join fos_user_user_group on fos_user_user.id = fos_user_user_group.user_id
                     left join fos_user_group on fos_user_user_group.group_id = fos_user_group.id
            where fos_user_group.code = :groupCode
              and fos_user_user.email = :email
        `;

        return knex.raw(query, {
            groupCode,
            email,
        }).then(([result]) => {
            return result[0] ? result[0] : null;
        });
    }
}