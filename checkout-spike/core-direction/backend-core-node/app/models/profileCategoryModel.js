const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class ProfileCategoryModel extends BaseModel {
    static get tableName() {
        return "ProfileCategory";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;
    }

    $beforeUpdate() {
        delete this.updated_date;
    }

    static findCompanyProfileCategoriesByUserId(user_id) {
        const query = `select pu.title,pu.image_url
 from user_profilecategory up 
inner join ProfileCategory pu on up.profilecategory_id = pu.id
where up.user_id=:user_id`;

        return knex.raw(query, {user_id}).then(([result]) => {
            return result;
        });
    }

    static getProfileCategoryFilter(user_id) {
        const query = `select pu.title,pu.image_url,pu.id
 from ProfileCategory pu where pu.is_deleted = 0 ORDER BY pu.order_data ASC`;

        return knex.raw(query, {user_id}).then(([result]) => {
            return result;
        });
    }

    static fetchUserProfiles(id) {

        const query = `select pc.image_url as category_image,pc.title as category_name 
 from user_profilecategory up 
inner join ProfileCategory pc on up.profilecategory_id = pc.id
where up.user_id=:id`;

        return knex.raw(query, {id,}).then(([result]) => {
            return result;
        });
    }

}
