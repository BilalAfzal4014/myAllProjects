const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");

module.exports = class PackageModel extends BaseModel {
    static get tableName() {
        return "package";
    }

    static findPackagesWithFacilityID(facility_id) {
        const query = `SELECT p.id as package_id , p.name, p.description, p.description_mobile, p.code,p.visits,p.public_rate,p.is_corepass,p.validity_days,p.discount,pt.name as package_name
from package as p 
INNER join package_type as pt on p.package_type_id = pt.id
WHERE p.is_deleted=0 and pt.is_deleted=0 and (p.expires_on > Date(NOW()) or p.repeat_monthly=1) and p.facility_id=:facility_id`;

        return knex.raw(query, {facility_id}).then(([result]) => {
            return result;
        });
    }

    static findPackagesWithCompanyID(facility_id) {
        const query = `SELECT p.id as package_id , p.name, p.description, p.description_mobile, p.code,p.visits,p.public_rate as price,p.corporate_rate,p.is_corepass,p.validity_days,p.discount,
 fuu.id as company_id ,fuu.company_logo,fuu.company_name as company_title,fuu.latitude,fuu.longitude, mp.id as member_package_id,pt.name as package_name
 from package as p 
LEFT join member_package as mp on p.id = mp.package_id
INNER join package_type as pt on p.package_type_id = pt.id
INNER join fos_user_user as fuu on p.facility_id = fuu.id
WHERE p.is_deleted=0 and pt.is_deleted=0 and (p.expires_on > Date(NOW())) and p.facility_id=:facility_id GROUP BY p.id`;

        return knex.raw(query, {facility_id}).then(([result]) => {
            return result;
        });
    }

    static findPackagesDetail(package_ids) {
        const query = `SELECT p.id as package_id , p.name, p.description, p.description_mobile, p.code,p.visits,p.public_rate as price,p.corporate_rate,p.is_corepass,p.validity_days,p.discount,
 fuu.id as company_id ,fuu.company_logo,fuu.company_name as company_title,fuu.latitude,fuu.longitude, mp.id as member_package_id,pt.name as package_name
from package as p 
INNER join member_package as mp on p.id = mp.package_id
INNER join package_type as pt on p.package_type_id = pt.id
INNER join fos_user_user as fuu on p.facility_id = fuu.id
WHERE  p.id IN(:package_ids) and p.is_deleted=0 and pt.is_deleted=0`;

        return knex.raw(query, {package_ids}).then(([result]) => {
            return result;
        });
    }


}
