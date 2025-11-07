const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");
const CompanyModuleConstants = require("../constants/company-module");
module.exports = class MemberScheduleActivityModel extends BaseModel {
    static get tableName() {
        return "member_schedule_activity";
    }

    static getBookedSlots(id) {

        let query = `SELECT COUNT(1) as booked_slots FROM member_schedule_activity WHERE schedule_detail_id = ${id} AND (STATUS IN ('booked')  OR (STATUS IN ('expired') AND checkin = 1)); `;
        return knex.raw(query, {}).then(([result]) => {
            return result[0].booked_slots;
        });
    }

    static fetchPackages(id, packageDetails) {
        let query = `SELECT p.id as package_id , p.name, p.description, p.description_mobile, p.code,p.visits,p.public_rate as price,p.corporate_rate,p.is_corepass,p.validity_days,p.discount,
 fuu.id as company_id ,fuu.company_logo,fuu.company_name as company_title,fuu.latitude,fuu.longitude,pt.name as package_name`;
        if (!packageDetails) {
            query += ` FROM activity_schedule_package asp INNER JOIN package p ON asp.package_id = p.id LEFT JOIN member_package AS mp ON p.id = mp.package_id INNER JOIN fos_user_user AS fuu ON p.facility_id = fuu.id INNER JOIN package_type AS pt ON p.package_type_id = pt.id  WHERE p.is_deleted = 0  AND p.expires_on > curdate()  AND asp.activityschedule_id =:id GROUP BY p.id `;
        } else {
            query += ` FROM   package p
        LEFT join member_package as mp on p.id = mp.package_id
        INNER join fos_user_user as fuu on p.facility_id = fuu.id
        INNER join package_type as pt on p.package_type_id = pt.id where p.id=:id  group by p.id `;
        }
        return knex.raw(query, {id}).then(([result]) => {
            return result;
        });
    }

    static fetchActivities(body) {

        let member_id = body.member_id;
        let activities_days_limit = process.env.schedule_detail_end_day;

        // filters variables
        let start_date = body.start_date;
        let is_online = body.is_online;
        let end_date = body.end_date
        let keyword = body.keyword;
        let activity_type_ids = body.activity_type_ids;
        let zone_ids = body.zone_ids;
        let profile_cat_id = body.profile_cat_id;
        let lat = body.lat;
        let lng = body.lng;
        let isFree = body.is_free;


        let date_filter_query = '';
        let keyword_filter_query = '';
        let activity_type_filter_query = '';
        let zone_filter_query = '';
        let online_query = '';
        let profile_cat_id_query = '';
        let distance_query = '';
        let distance_query_where = ` ORDER BY  schedule_date `;


        if (profile_cat_id && profile_cat_id.length) {
            profile_cat_id_query = ` AND  upc.profilecategory_id IN (:profile_cat_id) `;
        }
        if (is_online === 0 || is_online === false) {

            online_query = ` AND a2_.offer_online != 1 `;
        }
        if (is_online === 1 || is_online === true) {

            online_query = ` AND a2_.offer_online = 1 `;
        }

        let isFreeQuery = ``;
        if(isFree){

            switch (isFree){

                //get free classes only
                case 1:

                    isFreeQuery = ` a2_.is_free =1 `;
                    break;
                //    get paid classes
                case 2:

                    isFreeQuery = ` a2_.is_free =0 `;
                    break;
                default:
                    break;
            }
        }
        let currentdate = new Date();
        let curtime = currentdate.getHours() + ":"
            + currentdate.getMinutes() + ":"
            + currentdate.getSeconds();
        if (start_date != null && end_date != null) {

            start_date = body.start_date;
            end_date = body.end_date + ' 23:59:59'

            let inputDate = new Date(start_date);

            if (inputDate.setHours(0, 0, 0, 0) != currentdate.setHours(0, 0, 0, 0)) {
                curtime = '00:00:00';
            }
            date_filter_query = ` AND s3_.schedule_date >=  '${start_date} ${curtime}' AND s3_.schedule_date <= '${end_date}' `;
        } else {

            date_filter_query = ` AND DATE_ADD(s3_.schedule_date, INTERVAL s3_.duration MINUTE ) >  now() 
                                AND s3_.schedule_date <= DATE_ADD(now(), INTERVAL :activities_days_limit DAY)  `
        }
        if (keyword) {
            keyword_filter_query = `AND  (f1_.company_name LIKE :keyword OR f1_.address  LIKE :keyword  OR a2_.pinaddress  LIKE :keyword OR at_.name LIKE :keyword) `;
            keyword = '%' + keyword + '%';
        }
        if (activity_type_ids && activity_type_ids.length) {
            activity_type_filter_query = ` AND  at_.id IN (:activity_type_ids) `;
        }
        if (zone_ids && zone_ids.length) {
            zone_filter_query = ` AND  a2_.facilityZone_id IN (:zone_ids) `;
        }

        if (lat && lng) {

            distance_query = ` (
                6371 *
                acos(cos(radians(${lat})) *
                cos(radians(IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude))) *
                cos(radians(IF( a2_.is_location = 0 , f1_.longitude, a2_.longitude)) -
                    radians(${lng})) +
                    sin(radians(${lat})) *
                sin(radians(IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude))))
                ) AS distance,`;

            distance_query_where = ` ORDER BY
                                       distance DESC; `;

        }

        const query = `SELECT DISTINCT
                                  s3_.id AS schedule_detail_id,
                                    fug_.code f_code,
                                    fz.title zone,
                                  IFNULL(m3_.booked_slots,0) booked_slots,
                                  s3_.slots AS slots,
                                  s3_.schedule_id AS classId,
                                  DATE_FORMAT(DATE_ADD(s3_.schedule_date, INTERVAL s3_.duration MINUTE ), '%h:%i %p') endTime,
                                  a4_.id AS activityId,
                                  a4_.NAME AS name,
                                  at_.NAME AS actt_name,
                                  a4_.CODE AS code,
                                  a4_.description AS description,
                                  a4_.imageName AS activityImage,
                                  at_.imageName AS activityTypeImage,
                                  a2_.is_free AS isFree,
                                  instrutor_.title AS instructor_name,
                                  a2_.allow_corepass AS isCorePass,
                                  a2_.recurrence AS recurrence, 
                                  a2_.facility_id AS facilityId,
                                  f1_.company_name AS facility,
                                  IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude)  AS classLatitude,
                                  IF( a2_.is_location = 0 , f1_.longitude, a2_.longitude)  AS classLongitude,
                                  IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude)  AS facilityLatitude,
                                  IF( a2_.is_location = 0 , f1_.longitude, a2_.longitude)  AS facilityLongitude,
                                  IF( a2_.is_location = 1 AND  LENGTH (a2_.pinaddress ) > 0 , a2_.pinaddress, f1_
                                  .address)  AS address,
                                  IF( a2_.is_location = 1 AND  LENGTH (a2_.pinaddress ) > 0 , a2_.pinaddress, f1_.address)  AS activity_schedule_address,
                                  f1_.company_logo AS facilityImage,
                                  a2_.startDate AS a_startDate,
                                  a2_.endDate AS endDate,
                                  DATE_FORMAT(s3_.schedule_date,'%a,%M %D %Y') AS startDate,
                                  DATE_FORMAT(s3_.schedule_date,'%h:%i %p') AS startTime,
                                  a2_.offer_online AS offer_online,
                                  a2_.updated_date AS lastUpdated,
                                  m0_.is_favourite AS is_favourite_0,
                                  m0_.checkin AS checkin_1,
                                  IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude)  AS latitude,
                                  IF( a2_.is_location = 0 , f1_.longitude, a2_.longitude)  AS longitude,
                                  a2_.id AS id_5,
                                   s3_.schedule_date AS classDate,
                                  s3_.duration AS duration,
                                  ${distance_query}
                                  a2_.duration AS slot_duration,
                                  DATE_ADD(s3_.schedule_date, INTERVAL s3_.duration MINUTE ) class_end_date_time,
                                  a2_.is_recommended AS is_recommended
                                   FROM
                                  activity_schedule a2_
                                INNER JOIN schedule_detail s3_ ON a2_.id = s3_.schedule_id
                                
                                INNER JOIN activity a4_ ON a2_.activity_id = a4_.id 
                                INNER JOIN activity_type at_ ON a4_.activity_type_id = at_.id 
                                LEFT JOIN instructor instrutor_ ON
                                            s3_.instructor_id = instrutor_.id AND(instrutor_.is_deleted = 0)
                                INNER JOIN fos_user_user f1_ ON a2_.facility_id = f1_.id
                                INNER JOIN fos_user_user_group fuug_ ON f1_.id = fuug_.user_id
                                INNER JOIN fos_user_group fug_ ON fug_.id = fuug_.group_id
                                INNER JOIN user_profilecategory upc on upc.user_id = f1_.id
                               INNER JOIN facility_zones fz ON fz.id = a2_.facilityZone_id
                                INNER JOIN activity_schedule_package a6_ ON
                                    a2_.id = a6_.activityschedule_id
                                INNER JOIN package p5_ ON
                                    p5_.id = a6_.package_id
                                LEFT JOIN member_schedule_activity m0_ ON m0_.schedule_detail_id = s3_.id 
                                LEFT JOIN (
                                SELECT
                                    count(id) booked_slots,
                                    schedule_detail_id
                                FROM
                                    member_schedule_activity 
                                WHERE
                                  STATUS IN ('${CompanyModuleConstants.STATUS_BOOKED}','${CompanyModuleConstants.STATUS_RESERVED}','${CompanyModuleConstants.STATUS_EXPIRED}')
                                  group by schedule_detail_id
                                ) m3_ ON m3_.schedule_detail_id = s3_.id 
                            WHERE
                               
                                a2_.is_deleted = 0    
                                ${profile_cat_id_query}
                                ${keyword_filter_query}
                                ${activity_type_filter_query}
                                ${zone_filter_query}
                                ${online_query} 
                                ${isFreeQuery}   
                                AND ( f1_.enabled = 1 ) 
                                AND s3_.is_deleted = 0 
                                AND s3_.is_cancel = 0 
                                AND (a2_.is_free = 1 OR (p5_.is_deleted = 0 AND ( p5_.expires_on > curdate() OR p5_.repeat_monthly = 1 ) ))
                                ${date_filter_query}      
                                GROUP BY s3_.id
                                    ${distance_query_where} `;

        console.log(query);
        return knex.raw(query, {
            activities_days_limit, keyword, profile_cat_id, start_date, end_date, activity_type_ids, zone_ids
        }).then(([result]) => {
            return result;
        });
    }


    static fetchUserBookingHistory(body) {

        let member_id = body.member_id;


        const query = `  SELECT
    aType.id AS activity_type_id,
    aType.code AS activity_type_code,
    msa.checkin,
    p.id AS packageId,
    msa.is_favourite,
    msa.id,
    msa.updated_date,
    msa.created_date,
    msa.status,
    p.visits,
    p.name,
    p.public_rate,
    p.validity_days,
    p.is_corepass,
    u.latitude,
    u.longitude,
    u.timezone,
    act_sch.id AS classId,
    act_sch.startDate,
    act_sch.endDate,
    act_sch.recurrence,
    act_sch.is_recommended,
    act_sch.duration,
    sch_dtl.duration AS slot_duration,
    act.id AS activityId,
    act.name AS activityName,
    act.code,
    act.description,
    DATE_FORMAT(sch_dtl.schedule_date,'%h:%i %p') AS startTime,
    DATE_FORMAT(DATE_ADD(sch_dtl.schedule_date, INTERVAL sch_dtl.duration MINUTE ), '%h:%i %p') endTime,
    act.imageName AS activityImage,
    act_sch.offer_online AS online_class,
    act_sch.latitude AS class_latitude,
    act_sch.longitude AS class_longitude,
    act_sch.is_location,
    u.id AS facilityId,
    u.company_name AS facility,
    u.company_logo AS facilityImage,
    u.phone,
    u.city,
        fz.title zone_title,
                                  instrutor_.title AS instructor_name,
    u.address,
    u.timezone,
    act_sch.is_free,
    sch_dtl.id scheduleDetailId,
    sch_dtl.schedule_date scheduleDate,
        DATE_FORMAT(sch_dtl.schedule_date,'%M  %Y') AS month,
    DATE_FORMAT(sch_dtl.schedule_date,'%d-%m-%Y') AS class_date

FROM
    member_schedule_activity msa
INNER JOIN schedule_detail sch_dtl ON
    (
        sch_dtl.id = msa.schedule_detail_id && sch_dtl.is_deleted = 0
    )
INNER JOIN activity_schedule act_sch ON
    (
        act_sch.id = sch_dtl.schedule_id && act_sch.is_deleted = 0
    )
INNER JOIN package p ON
    p.id = msa.package_id
INNER JOIN activity act ON
    act.id = act_sch.activity_id
INNER JOIN activity_type aType ON
    aType.id = act.activity_type_id
INNER JOIN fos_user_user u ON
    u.id = act_sch.facility_id
LEFT JOIN instructor instrutor_ ON
                                            sch_dtl.instructor_id = instrutor_.id AND(instrutor_.is_deleted = 0)
LEFT JOIN
 facility_zones fz ON fz.id= act_sch.facilityZone_id
WHERE
    msa.is_deleted = 0 AND msa.STATUS IN('${CompanyModuleConstants.STATUS_CANCELLED}','${CompanyModuleConstants.STATUS_EXPIRED}') AND msa.member_id =:member_id
ORDER BY
    msa.updated_date
DESC `

        return knex.raw(query, {
            member_id
        }).then(([result]) => {
            return result;
        });
    }


    static fetchUserUpcomingBooking(body) {

        let member_id = body.member_id;


                    const query = `  SELECT
                aType.id AS activity_type_id,
                aType.code AS activity_type_code,
                aType.imageName AS activityTypeImage,
                aType.NAME AS actt_name,
                msa.checkin,
                p.id AS packageId,
                msa.is_favourite,
                msa.id,
                msa.updated_date,
                msa.created_date,
                msa.status,
                IFNULL(m3_.booked_slots,0) booked_slots,
                p.visits,
                p.name,
                p.public_rate,
                p.validity_days,
                p.is_corepass,
                u.latitude,
                u.longitude,
                u.timezone,
                act_sch.id AS classId,
                act_sch.startDate,
                act_sch.endDate,
                act_sch.recurrence,
                act_sch.is_recommended,
                act_sch.duration,
                sch_dtl.duration AS slot_duration,
                act.id AS activityId,
                act.name AS activityName,
                act.code,
                act.description,
                act.imageName AS activityImage,
                act_sch.offer_online AS online_class,
                act_sch.latitude AS class_latitude,
                act_sch.longitude AS class_longitude,
                act_sch.is_location,
                u.id AS facilityId,
                u.company_name AS facility,
                u.company_logo AS facilityImage,
                u.phone,
                u.city,
                fz.title zone_title,
                instrutor_.title AS instructor_name,
                u.address,
                u.timezone,
                DATE_FORMAT(sch_dtl.schedule_date,'%h:%i %p') AS startTime,
                DATE_FORMAT(DATE_ADD(sch_dtl.schedule_date, INTERVAL sch_dtl.duration MINUTE ), '%h:%i %p') endTime,
                act_sch.is_free,
                sch_dtl.id scheduleDetailId,
                sch_dtl.slots AS slots,
                sch_dtl.schedule_date scheduleDate,
                DATE_FORMAT(sch_dtl.schedule_date,'%d-%m-%Y') AS class_date,
                DATE_FORMAT(sch_dtl.schedule_date,'%M  %Y') AS month
            FROM
                member_schedule_activity msa
            INNER JOIN schedule_detail sch_dtl ON
                (
                    sch_dtl.id = msa.schedule_detail_id && sch_dtl.is_deleted = 0
                )
            INNER JOIN activity_schedule act_sch ON
                (
                    act_sch.id = sch_dtl.schedule_id && act_sch.is_deleted = 0
                )
            INNER JOIN package p ON
                p.id = msa.package_id
            INNER JOIN activity act ON
                act.id = act_sch.activity_id
            INNER JOIN activity_type aType ON
                aType.id = act.activity_type_id
            INNER JOIN fos_user_user u ON
                u.id = act_sch.facility_id
            LEFT JOIN instructor instrutor_ ON
                  sch_dtl.instructor_id = instrutor_.id AND(instrutor_.is_deleted = 0)
            LEFT JOIN
             facility_zones fz ON fz.id= act_sch.facilityZone_id
             
             LEFT JOIN (
                                SELECT
                                    COUNT(id) booked_slots, schedule_detail_id
                                FROM
                                    member_schedule_activity 
                                WHERE
                                  STATUS IN ('${CompanyModuleConstants.STATUS_BOOKED}','${CompanyModuleConstants.STATUS_RESERVED}')
                                  OR (STATUS = '${CompanyModuleConstants.STATUS_EXPIRED}' AND checkin = 1)
                                  group by schedule_detail_id
                                ) m3_ ON m3_.schedule_detail_id = sch_dtl.id 
                                
                                
            WHERE
                msa.is_deleted = 0 AND msa.STATUS IN( '${CompanyModuleConstants.STATUS_BOOKED}') AND msa.member_id = :member_id AND sch_dtl.schedule_date >= NOW()
            ORDER BY
                sch_dtl.schedule_date ASC `

        return knex.raw(query, {
            member_id
        }).then(([result]) => {
            return result;
        });
    }

    static fetchUserCalendarBooking(body) {

        let member_id = body.member_id;


        const query = `  SELECT
    aType.id AS activity_type_id,
    aType.code AS activity_type_code,
    msa.checkin,
    p.id AS packageId,
    msa.is_favourite,
    msa.id,
    msa.updated_date,
    msa.created_date,
    msa.status,
    p.visits,
    p.name,
    p.public_rate,
    p.validity_days,
    p.is_corepass,
    u.latitude,
    u.longitude,
    u.timezone,
    act_sch.id AS classId,
    act_sch.startDate,
    act_sch.endDate,
    act_sch.recurrence,
    act_sch.is_recommended,
    act_sch.duration,
    sch_dtl.duration AS slot_duration,
    act.id AS activityId,
    act.name AS activityName,
    act.code,
    act.description,
    act.imageName AS activityImage,
    act_sch.offer_online AS online_class,
    act_sch.latitude AS class_latitude,
    act_sch.longitude AS class_longitude,
    act_sch.is_location,
    u.id AS facilityId,
    u.company_name AS facility,
    u.company_logo AS facilityImage,
    u.phone,
    u.city,
    u.address,
    fz.title zone_title,
    instrutor_.title AS instructor_name,
    u.timezone,
    act_sch.is_free,
    sch_dtl.id scheduleDetailId,
    sch_dtl.schedule_date scheduleDate,
    DATE_FORMAT(sch_dtl.schedule_date,'%h:%i %p') AS startTime,
    DATE_FORMAT(DATE_ADD(sch_dtl.schedule_date, INTERVAL sch_dtl.duration MINUTE ), '%h:%i %p') endTime,
    DATE_FORMAT(sch_dtl.schedule_date,'%d-%m-%Y') AS class_date
FROM
    member_schedule_activity msa
INNER JOIN schedule_detail sch_dtl ON
    (
        sch_dtl.id = msa.schedule_detail_id && sch_dtl.is_deleted = 0
    )
INNER JOIN activity_schedule act_sch ON
    (
        act_sch.id = sch_dtl.schedule_id && act_sch.is_deleted = 0
    )
INNER JOIN package p ON
    p.id = msa.package_id
INNER JOIN activity act ON
    act.id = act_sch.activity_id
INNER JOIN activity_type aType ON
    aType.id = act.activity_type_id
INNER JOIN fos_user_user u ON
    u.id = act_sch.facility_id
LEFT JOIN instructor instrutor_ ON
                                            sch_dtl.instructor_id = instrutor_.id AND(instrutor_.is_deleted = 0)
LEFT JOIN
 facility_zones fz ON fz.id= act_sch.facilityZone_id
WHERE
    msa.is_deleted = 0 AND msa.STATUS IN( '${CompanyModuleConstants.STATUS_BOOKED}') AND msa.member_id = :member_id AND sch_dtl.schedule_date >= NOW()
GROUP BY class_date

ORDER BY
    msa.updated_date
DESC `


        return knex.raw(query, {
            member_id
        }).then(([result]) => {
            return result;
        });
    }


    static fetchUsedSlots(slot_id, includingUsers = null, transaction = null) {

        let query = `SELECT COUNT(member_schedule_activity.id) as used_slots
                       FROM member_schedule_activity
                       WHERE member_schedule_activity.schedule_detail_id = :slot_id AND`

        if (includingUsers) {
            query += `(
                    (member_schedule_activity.STATUS = :reserved and member_id not in (:includingUsers))
                    OR
                    (member_schedule_activity.STATUS = :booked)
                )`;
        } else {
            query += `(
                member_schedule_activity.STATUS = :booked OR member_schedule_activity.STATUS = :reserved
            )`
        }

        const knexChain = knex.raw(query, {
            slot_id,
            booked: CompanyModuleConstants.STATUS_BOOKED,
            reserved: CompanyModuleConstants.STATUS_RESERVED,
            ...(includingUsers && {includingUsers})
        })
        if (transaction) {
            knexChain.transacting(transaction)
        }

        return knexChain
            .then(([result]) => {
                return result;
            });
    }

    static fetchTotalSlots(slot_id, transaction = null) {

        const query = `SELECT
    SUM(schedule_detail.slots) as total_slots
FROM
    schedule_detail
WHERE
    schedule_detail.id = :slot_id AND schedule_detail.is_deleted = 0 AND schedule_detail.is_cancel = 0`


        const knexChain = knex.raw(query, {
            slot_id
        })
        if (transaction) {
            knexChain.transacting(transaction)
        }

        return knexChain
            .then(([result]) => {
                return result;
            });
    }

    static checkIfUserAlreadyHasBooking(member_id, slot_id) {
        const query = `SELECT * FROM member_schedule_activity where member_id=:member_id and schedule_detail_id=:slot_id and is_deleted=0 and STATUS='booked' `

        return knex.raw(query, {
            member_id,
            slot_id
        }).then(([result]) => {
            return result;
        });
    }

    static checkIfUserAlreadyHasBookingOrReservedBooking(member_id, slot_id) {
        const query = `SELECT * FROM member_schedule_activity where member_id=:member_id and schedule_detail_id=:slot_id and is_deleted=0 and (STATUS='booked' OR STATUS='reserved')`

        return knex.raw(query, {
            member_id,
            slot_id
        }).then(([result]) => {
            return result;
        });
    }

    static saveQR(id, qr_code) {

        const query = `UPDATE member_schedule_activity SET qr_code = :qr_code WHERE id = :id;`
        return knex.raw(query, {
            qr_code,
            id
        }).then(([result]) => {
            return result;
        });
    }

    static fetchEmailData(slot_id) {
        const query = `SELECT
        msa.id,
        _member.confirmation_token,
        fz.title zone_title,
        _act.imageName activity_image,
msa.created_date,
sd.duration class_duration,
DATE_FORMAT( sd.schedule_date, '%d-%m-%Y' ) AS class_date,
DATE_FORMAT( sd.schedule_date, '%h:%i %p' ) AS startTime,
DATE_FORMAT(DATE_ADD(sd.schedule_date, INTERVAL sd.duration MINUTE ), '%h:%i %p') endTime,
_act.name activity_name,
_act.description activity_description,
_as.offer_online,
_as.instruction_file,
_facility.email facility_email,
_as.login_url_online,
_as.login_password_online, 
_as.meeting_id_online, 
_actt.name activity_type_name,
CONCAT(_member.firstname,' ',_member.lastname) as member_name,
_member.email as member_email,
   IF(
        _as.city is null,
        _facility.city,
        _as.city
    ) AS city,
    _facility.company_name AS facility,
    IF(
        _as.is_location = 0,
        _facility.latitude,
        _as.latitude
    ) AS classLatitude,
    IF(
        _as.is_location = 0,
        _facility.longitude,
        _as.longitude
    ) AS classLongitude,
    IF(
        _as.is_location = 0,
        _facility.latitude,
        _as.latitude
    ) AS facilityLatitude,
    IF(
        _as.is_location = 0,
        _facility.longitude,
        _as.longitude
    ) AS facilityLongitude,
    IF(
        _as.is_location = 1 AND LENGTH(_as.pinaddress) > 0,
        _as.pinaddress,
        _facility.address
    ) AS address,
    IF(
        _as.is_location = 1 AND LENGTH(_as.pinaddress) > 0,
        _as.pinaddress,
        _facility.address
    ) AS activity_schedule_address
FROM
member_schedule_activity msa
INNER JOIN schedule_detail sd ON sd.id = msa.schedule_detail_id
INNER JOIN activity_schedule _as ON _as.id = sd.schedule_id
INNER JOIN activity _act ON _act.id = _as.activity_id
INNER JOIN activity_type _actt ON _actt.id = _act.activity_type_id
INNER JOIN fos_user_user _facility ON _facility.id = _as.facility_id
INNER JOIN fos_user_user _member ON _member.id = msa.member_id
LEFT JOIN facility_zones fz ON fz.id = _as.facilityZone_id
WHERE msa.id = :slot_id;`
        return knex.raw(query, {slot_id}).then(([result]) => {
            return result;
        });
    }

    static checkIsActivityExistsAndBooked(body) {

        let member_id = body.member_id;
        let slot_id = body.slot_id;

        const query = `SELECT * FROM member_schedule_activity where member_id=:member_id and id=:slot_id and is_deleted=0 and STATUS='${CompanyModuleConstants.STATUS_BOOKED}'`

        return knex.raw(query, {
            member_id,
            slot_id
        }).then(([result]) => {
            return (result.length > 0) ? true : false;
        });
    }

    static cancelBookedActivity(body) {

        let member_id = body.member_id;
        let slot_id = body.slot_id;

        const query = `UPDATE  member_schedule_activity SET STATUS='${CompanyModuleConstants.STATUS_CANCELLED}' where member_id=:member_id and id=:slot_id and is_deleted=0 and STATUS='${CompanyModuleConstants.STATUS_BOOKED}'`
        return knex.raw(query, {
            member_id,
            slot_id
        }).then(([result]) => {
            return (result.affectedRows) ? true : false;
        });
    }

    static checkInActivity(body) {

        try {
            let member_id = body.member_id;
            let slot_id = body.slot_id;

            const query = `UPDATE  member_schedule_activity SET STATUS='${CompanyModuleConstants.STATUS_EXPIRED}', reminder=1 ,checkin=1 where  id=${slot_id} `
            return knex.raw(query, {
                member_id,
                slot_id
            }).then(([result]) => {
                console.log(result)
                return (result.affectedRows) ? true : false;
            });
        } catch (err) {
            console.log(err)
        }
    }

    static acquireLockOnTable(transaction = null) {
        const query = `LOCK TABLES member_schedule_activity WRITE;`;
        return knex.raw(query)
            .transacting(transaction);
    }

    static unLockTable(transaction = null) {
        const query = `UNLOCK TABLES;`;
        return knex.raw(query)
            .transacting(transaction);
    }

    static returnTime() {
        var d = new Date();
        let h = (d.getHours() < 10 ? '0' : '') + d.getHours();
        let m = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();
        let s = (d.getSeconds() < 10 ? '0' : '') + d.getSeconds();
        return ' ' + h + ':' + m + ':' + ':' + s
    }
}
