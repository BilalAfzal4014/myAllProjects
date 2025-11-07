const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class CorporateKeyModel extends BaseModel {
    static get tableName() {
        return "corporate_key";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;

    }

    $beforeUpdate() {
        delete this.updated_date;
    }

    static fetchActiveNonDefaultKeyWithCompanyKey(company_key) {
        let now = new Date().toLocaleString();
        let notInclude = 'Default';
        const query = `
            SELECT * FROM corporate_key
             WHERE company_key LIKE :company_key 
             AND validate_date >= now()
             AND is_active = 1
            AND \`type\` != :notInclude
        `;


        return knex.raw(query, {
            company_key,
            now,
            notInclude
        }).then(([result]) => {
            return result[0] ? result[0] : null;
        });

    }

    static isDiscountExistForUser(member_id) {
        const query = `SELECT ck.type 
from member_key as mk
inner join corporate_key as ck on mk.member_id = ck.corporate_id
where ck.is_deleted=0 and ck.is_active=1 and ck.type='Discount' and mk.member_id=:member_id;`;

        return knex.raw(query, {
            member_id
        }).then(([result]) => {
            return result.length > 0 ? true : false;
        });

    }
    static getKeyInfo(key) {
        const query = `SELECT * from corporate_key as ck where ck.is_deleted=0 and ck.is_active=1 and ck.company_key=:key;`;
        return knex.raw(query, {
            key
        }).then(([result]) => {
            return result[0];
        });
    }


}
