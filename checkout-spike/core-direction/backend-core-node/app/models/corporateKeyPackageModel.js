const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class CorporateKeyPackageModel {
    static get tableName() {
        return "corporate_key_package";
    }
    static getCorporatePacakges(corporate_key_id) {
        const query = `SELECT * from corporate_key_package ckp  where ckp.corporatekey_id=:corporate_key_id;`;
        return knex.raw(query, {corporate_key_id
        }).then(([result]) => {
            return result;
        });
    }


}
