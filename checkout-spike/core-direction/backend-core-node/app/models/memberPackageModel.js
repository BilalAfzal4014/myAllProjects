const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class MemberPackageModel extends BaseModel {
    static get tableName() {
        return "member_package";
    }

    static fetchMemberPackages(payLoad) {
        let user_id = payLoad.user_id;
        let company_ids = (payLoad.company_ids) ? payLoad.company_ids : null;
        let status = (payLoad.status) ? payLoad.status : null;
        let date = (payLoad.date) ? payLoad.date+' 23:59:59' : null;

        let company_query = ''
        let status_query = ''
        let date_query = ''

        if (company_ids && company_ids.length>0) {
            company_query = ` AND fuu.id IN (:company_ids) `;
        }
        if (status && status.length>0) {
            status_query = ` AND  mp.status IN (:status) `;
        }
        if (date) {
            date_query = ` and p.created_date > Date('${date}') `;
        }

        let query = `SELECT  p.id as package_id , p.name, p.description, p.description_mobile,p.code,p.visits,p.public_rate as price,p.corporate_rate,p.is_corepass,p.validity_days,p.discount,
 fuu.id as company_id ,fuu.company_logo,fuu.company_name as company_title,fuu.latitude,fuu.longitude,mp.status, mp.id as member_package_id,pt.name as package_name
 from package as p 
INNER join member_package as mp on p.id = mp.package_id
INNER join package_type as pt on p.package_type_id = pt.id
INNER join fos_user_user as fuu on p.facility_id = fuu.id
WHERE p.is_deleted=0  and (p.expires_on > Date(NOW())) and mp.member_id=:user_id ${company_query} ${date_query}  ${status_query} `

        return knex.raw(query, {user_id,company_ids,status}).then(([result]) => {
            return result;
        });
    }

    static addInMemberPackage(payload) {

        let member_id = payload.member_id;
        let package_id = payload.package_id;
        const query = `INSERT INTO member_package (member_id, package_id, status, is_deleted, is_promotion, checkin,modifiedby,created_date,updated_date)
        VALUES (:member_id,:package_id,'active',0,1,0,:member_id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP());`
        return knex.raw(query, {
            member_id,
            package_id
        }).then(([result]) => {
            return result;
        });
    }

    static fetchClassMemberPackages(payload) {

        let member_id = payload.member_id;
        let class_id = payload.class_id;
        const query = `SELECT DISTINCT p.id as package_id , p.name, p.description, p.description_mobile, p.code,p.visits,p.public_rate as price,p.corporate_rate,p.is_corepass,p.validity_days,p.discount,
 fuu.id as company_id ,fuu.company_logo,fuu.company_name as company_title,fuu.latitude,fuu.longitude, mp.id as member_package_id,pt.name as package_name
FROM member_package as mp
INNER JOIN package p on p.id = mp.package_id
INNER JOIN  package_type pt on pt.id = p.package_type_id
INNER JOIN fos_user_user fuu on fuu.id = p.facility_id
INNER JOIN activity_schedule_package asp on asp.package_id = p.id
where mp.member_id=:member_id and asp.activityschedule_id = :class_id and p.is_deleted=0 and mp.status = 'active' and (p.expires_on > Date(NOW())) and mp.checkin < p.visits`
        return knex.raw(query, {
            member_id,
            class_id
        }).then(([result]) => {
            return result;
        });
    }

    static fetchSpecificPackageOfAMemberByIdWithWriteLock(id, package_id, member_id, transaction) {

        const knexChain = knex('member_package');

        if (transaction) {
            knexChain.transacting(transaction)
                .forUpdate();
        }

        return knexChain.where({
            id,
            package_id,
            member_id,
            status: "active"
        }).select('*');

    }

    static decCheckin(id) {

        const query = `UPDATE member_package SET checkin = GREATEST(0, checkin - 1),status='active' WHERE id =:id`
        return knex.raw(query, {
            id,
        }).then(([result]) => {
            return result;
        });
    }

}
