const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class MemberKeyModel extends BaseModel {
    static get tableName() {
        return "member_key";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;

    }

    $beforeUpdate() {
        delete this.updated_date;
    }

    static isKeyRedeemed(payload) {
        let key_id = payload.key_id
        let member_id = payload.member_id
        const query = `SELECT * from member_key as mk where mk.key_id=:key_id and mk.member_id=:member_id;`;
        return knex.raw(query, {
            key_id,member_id
        }).then(([result]) => {
            return result;
        });
    }
    static addRecord(payload) {
        let member_id = payload.member_id;
        let key_id = payload.key_id;
        const query = `INSERT INTO member_key (member_id, key_id,created) VALUES (:member_id,:key_id,CURRENT_TIMESTAMP());`
        return knex.raw(query, {
            member_id,
            key_id
        }).then(([result]) => {
            return result;
        });
    }
    static checkUserHasDiscount(user_id) {

        const query = `INSERT INTO member_key (member_id, key_id,created) VALUES (:member_id,:key_id,CURRENT_TIMESTAMP());`
        return knex.raw(query, {
            member_id,
            key_id
        }).then(([result]) => {
            return result;
        });
    }
    static isDiscountExistForUser(member_id) {
        const query = `SELECT ck.type 
from member_key as mk
inner join corporate_key as ck on mk.key_id = ck.id
where ck.is_deleted=0 and ck.is_active=1 and ck.type='Discount' and mk.member_id=:member_id;`;

        return knex.raw(query, {
            member_id
        }).then(([result]) => {
            return result.length > 0 ? true : false;
        });

    }
    static countConsumed(member_package_id,member_id) {
       // const query = `SELECT COUNT(*) as count from member_schedule_activity as msa where msa.member_package_id=:member_package_id;`;
        const query = `SELECT * FROM member_package where member_id=:member_id and id=:member_package_id and is_deleted=0 and status='active';`;
        return knex.raw(query, {
            member_package_id,member_id
        }).then(([result]) => {
            return (result[0])?result[0].checkin:0
        });

    }
}
