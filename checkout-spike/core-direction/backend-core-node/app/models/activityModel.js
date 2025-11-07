const BaseModel = require("./baseModel");
const {knex} = require("../databases/sql-connection");
const CompanyModuleConstants = require("../constants/company-module");
module.exports = class ActivityModel extends BaseModel {
    static get tableName() {
        return "activity";
    }

    static fetchCompanyActivities(body) {

        let member_id = (body.member_id != 0) ? body.member_id : null;
        let facility_id = body.facility_id;

        let unit = 6371;
        let latitude = body.latitude;
        let longitude = body.longitude;

        let numberOfDaysToAdd = process.env.company_activity_no_of_days;

        let member_query_1 = '';
        let member_query_2 = '';
        let member_query_3 = '';
        let member_query_4 = '';
        let distance_query = '';
        let distance_orderby_query = '';

        if (body.member_id != null) {
            member_query_1 = ` AND m0_.member_id = ':member_id' `;
            member_query_2 = ` LEFT JOIN
                                  (SELECT
                                    schedule_detail_id ,IF(member_id = ':member_id' , 1,0) self_booked
                                    FROM
                                    member_schedule_activity
                                    WHERE
                                    status IN ('${CompanyModuleConstants.STATUS_BOOKED}','${CompanyModuleConstants.STATUS_RESERVED}')
                                    OR (STATUS = '${CompanyModuleConstants.STATUS_EXPIRED}' AND checkin = 1)
                                    GROUP BY schedule_detail_id,member_id) m4_ ON m4_.schedule_detail_id = s3_.id `;

            member_query_3 = ` OR IFNULL(m4_.self_booked,1)=1 `;
            member_query_4 = ` AND member_id = :member_id `;
        }
        if (body.latitude != null && body.longitude != null) {
            distance_query = ` ,(
                :unit *
                acos(cos(radians(:latitude)) *
                cos(radians(IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude))) *
                cos(radians(IF( a2_.is_location = 0 , f1_.longitude, a2_.longitude)) -
                    radians(:longitude)) +
                    sin(radians(:latitude)) *
                sin(radians(IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude))))
                ) AS distance`;

            distance_orderby_query = ` ORDER BY
                                       distance DESC `;
        }


        // filters variables
        let start_date = body.start_date;
        let end_date = body.end_date
        let keyword = body.keyword;
        let activity_type_ids = body.activity_type_ids;
        let zone_id = body.zone_ids;


        // filters quries
        let date_filter_query = '';
        let keyword_filter_query = '';
        let activity_type_filter_query = '';
        let zone_filter_query = '';

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

            date_filter_query = ` AND s3_.schedule_date >= '${start_date} ${curtime}' AND s3_.schedule_date <= :end_date `;
        } else {


            date_filter_query = `  AND DATE_ADD(s3_.schedule_date, INTERVAL s3_.duration MINUTE ) >  (SELECT _sd.schedule_date from schedule_detail _sd
INNER JOIN activity_schedule _as ON _as.id=_sd.schedule_id
where _as.facility_id = :facility_id and _sd.schedule_date >= NOW() ORDER BY _sd.schedule_date ASC LIMIT 1) 
                                AND s3_.schedule_date <= DATE_ADD(DATE_FORMAT((SELECT _sd.schedule_date from schedule_detail _sd
INNER JOIN activity_schedule _as ON _as.id=_sd.schedule_id
where _as.facility_id = :facility_id and _sd.schedule_date >= NOW() ORDER BY _sd.schedule_date ASC LIMIT 1),'%Y-%m-%d'), INTERVAL ${numberOfDaysToAdd} DAY) `
        }
        if (keyword) {
            keyword_filter_query = `AND  (f1_.company_name LIKE :keyword OR f1_.address  LIKE :keyword  OR a2_.pinaddress  LIKE :keyword OR at_.name LIKE :keyword) `;
            keyword = '%' + keyword + '%';
        }
        if (activity_type_ids && activity_type_ids.length) {
            activity_type_filter_query = ` AND  at_.id IN (:activity_type_ids) `;
        }

        if (zone_id && zone_id.length) {
            zone_filter_query = ` AND  a2_.facilityZone_id IN (:zone_id) `;
        }


        const query = `SELECT DISTINCT
                                  s3_.id AS schedule_detail_id,
                                  fz.title zone,
                                  IFNULL(m3_.booked_slots,0) booked_slots,
                                  s3_.slots AS slots,
                                  s3_.schedule_id AS classId,
                                  a4_.id AS activityId,
                                  a4_.NAME AS name,
                                                                    at_.NAME AS actt_name,

                                  a4_.CODE AS code,
                                  a4_.description AS description,
                                  a4_.imageName AS activityImage,
                                                                    at_.imageName AS activityTypeImage,

                                  a2_.is_free AS isFree,
                                  a2_.offer_online AS offer_online,
                                  a2_.allow_corepass AS isCorePass,
                                  a2_.recurrence AS recurrence,
                                  s3_.duration AS duration,
                                  DATE_FORMAT(s3_.schedule_date,'%h:%i %p') AS startTime,
                                  DATE_FORMAT(DATE_ADD(s3_.schedule_date, INTERVAL s3_.duration MINUTE ), '%h:%i %p') endTime,
                                  DATE_ADD(s3_.schedule_date, INTERVAL s3_.duration MINUTE ) class_end_date_time,
                                  instrutor_.title AS instructor_name,
                                  a2_.duration AS slot_duration,
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
                                  DATE_FORMAT(s3_.schedule_date,'%a,%M %D %Y') AS startDate,
                                  a2_.endDate AS endDate,
                                  a2_.updated_date AS lastUpdated,
                                  s3_.schedule_date AS classDate,
                                  m0_.is_favourite AS is_favourite_0,
                                  m0_.checkin AS checkin_1,
                                  m0_.STATUS AS status,
                                  m0_.id AS msa_id,
                                  IF( a2_.is_location = 0 , f1_.latitude, a2_.latitude)  AS latitude,
                                  IF( a2_.is_location = 0 , f1_.longitude, a2_.longitude)  AS longitude,
                                  a2_.id AS id_5,
                                  a2_.is_recommended AS is_recommended ${distance_query}
                                FROM
                                  activity_schedule a2_
                                INNER JOIN schedule_detail s3_ ON a2_.id = s3_.schedule_id
                                INNER JOIN activity a4_ ON a2_.activity_id = a4_.id
                                INNER JOIN facility_zones fz ON fz.id = a2_.facilityZone_id
                                INNER JOIN activity_type at_ ON a4_.activity_type_id = at_.id 
                                INNER JOIN activity_schedule_package a6_ ON
                                    a2_.id = a6_.activityschedule_id
                                INNER JOIN package p5_ ON
                                    p5_.id = a6_.package_id
                                LEFT JOIN instructor instrutor_ ON
                                            s3_.instructor_id = instrutor_.id AND(instrutor_.is_deleted = 0)
                                INNER JOIN fos_user_user f1_ ON a2_.facility_id = f1_.id
                                LEFT JOIN member_schedule_activity m0_ ON m0_.schedule_detail_id = s3_.id AND m0_.STATUS != '${CompanyModuleConstants.STATUS_CANCELLED}' ${member_query_1}
                                LEFT JOIN (
                                SELECT
                                    COUNT(id) booked_slots, schedule_detail_id
                                FROM
                                    member_schedule_activity 
                                WHERE
                                  STATUS IN ('${CompanyModuleConstants.STATUS_BOOKED}','${CompanyModuleConstants.STATUS_RESERVED}')
                                  OR (STATUS = '${CompanyModuleConstants.STATUS_EXPIRED}' AND checkin = 1)
                                  group by schedule_detail_id
                                ) m3_ ON m3_.schedule_detail_id = s3_.id 
                                
                                ${member_query_2}
                                    
                            WHERE
                                a2_.is_deleted = 0 
                                ${date_filter_query}  
                                ${keyword_filter_query}
                                ${activity_type_filter_query}
                                ${zone_filter_query}
                              AND ( f1_.enabled = 1 )
                                AND a2_.facility_id IN (:facility_id)
                                AND s3_.is_deleted = 0 
                                AND s3_.is_cancel = 0 
                                 AND (a2_.is_free = 1 OR (p5_.is_deleted = 0 AND ( p5_.expires_on > curdate() OR p5_.repeat_monthly = 1 ) ))
                            GROUP BY s3_.id 
                                    ORDER BY
                                 s3_.schedule_date
                                 LIMIT 100`;

        return knex.raw(query, {
            member_id,
            start_date,
            end_date,
            activity_type_ids,
            zone_id,
            facility_id,
            keyword,
            unit,
            latitude,
            longitude
        }).then(([result]) => {
            return result;
        });
    }
    static returnTime() {
        var d = new Date();
        let h = (d.getHours() < 10 ? '0' : '') + d.getHours();
        let m = (d.getMinutes() < 10 ? '0' : '') + d.getMinutes();
        let s = (d.getSeconds() < 10 ? '0' : '') + d.getSeconds();
        return ' ' + h + ':' + m + ':' + ':' + s
    }

}
