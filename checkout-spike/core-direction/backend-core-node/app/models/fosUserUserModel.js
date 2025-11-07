const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class FosUserUserModel extends BaseModel {
    static get tableName() {
        return "fos_user_user";
    }

    $beforeInsert() {
        delete this.created_date;
        delete this.updated_date;

        this.created_at = new Date();
        this.updated_at = new Date();
    }

    $beforeUpdate() {
        delete this.updated_date;
        this.updated_at = new Date();
    }

    static fetchCompanies(payload) {

        let profile_cat_id = payload.profile_cat_id;
        let keyword = (payload.keyword) ? payload.keyword : null;
        let latitude = (payload.latitude) ? payload.latitude : null;
        let longitude = (payload.longitude) ? payload.longitude : null;
        let distance_query = '';
        let distance_order_query = '';
        let keyword_query = '';


        if (latitude != null && latitude != null) {
            distance_query = ` ,(6371 *
                                        acos(cos(radians(:latitude)) *
                                             cos(radians(fuu.latitude)) *
                                             cos(radians(fuu.longitude) -
                                                 radians(:longitude)) +
                                             sin(radians(:latitude)) *
                                             sin(radians(fuu.latitude)))
                                   ) AS distance `;
            distance_order_query = ` ORDER BY
                                       distance DESC `;
        }

        if (keyword) {


            keyword_query = `  AND (fuu.company_name LIKE :keyword OR fuu.address LIKE :keyword) `;
            keyword = '%' + keyword + '%';
        }
        let query = `select fuu.id,fuu.latitude, fuu.longitude ,fuu.address,fuu.company_banner,fuu.company_logo,fuu.company_name ${distance_query}
            from fos_user_user fuu 
            inner join user_profilecategory up  on fuu.id = up.user_id
            inner join ProfileCategory pc on pc.id = up.profilecategory_id`;
        if (profile_cat_id) {

            query = query + ` where pc.id=:profile_cat_id ${keyword_query} and fuu.enabled = 1 `;
        } else {


            query = query + ` where pc.id > 0 ${keyword_query} and fuu.enabled = 1 `;
        }

        query = query + ` GROUP BY fuu.id ${distance_order_query}; `;

        return knex.raw(query, {profile_cat_id, latitude, longitude, keyword}).then(([result]) => {
            return result;
        });
    }

    static getFacilityCompanies() {
        let query = `SELECT fuu.* FROM fos_user_user as fuu
             inner join fos_user_user_group fuug on fuu.id = fuug.user_id 
             inner join fos_user_group fug on fuug.group_id  = fug.id 
             where fug.code='Facility';`


        return knex.raw(query, {}).then(([result]) => {
            return result;
        });

    }

}
