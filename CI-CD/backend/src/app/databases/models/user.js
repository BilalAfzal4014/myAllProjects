const BaseModel = require('./base');
const {knex} = require('../../databases/sql-connection');

module.exports = class UserModel extends BaseModel {
    static get tableName() {
        return 'user';
    }

    static getAllUsersListingWithRelevantGroupExceptTheCurrentOne(userId) {

        const query = `
            select
               groupQuery.*,
               ifnull(latestMessageQuery.text, '') as last_message 
            from
               (
                  SELECT
                     x2.group_id,
                     case
                        when
                           x2.total_users = 1
                        then
                           TRIM(BOTH '"' 
                  FROM
                     JSON_EXTRACT(x2.users, '$[0].name')) 
                  else
                     x2.group_name 
                     end
                     as group_name, x2.users 
                  FROM
                     (
                        SELECT
                           user_chat_group_id AS group_id,
                           group_name,
                           CONCAT('[', GROUP_CONCAT(CONCAT('{"id":', id, ',"name":"', name, '"}')), ']') AS users,
                           COUNT(*) AS total_users 
                        FROM
                           (
                              SELECT
                                 user.id,
                                 user.name,
                                 user_chat_group_member.user_chat_group_id,
                                 user_chat_group.name AS group_name 
                              FROM
                                 user 
                                 JOIN
                                    user_chat_group_member 
                                    ON user.id = user_chat_group_member.user_id 
                                 JOIN
                                    user_chat_group 
                                    ON user_chat_group_member.user_chat_group_id = user_chat_group.id 
                              WHERE
                                 user.id != :userId
                                 AND EXISTS 
                                 (
                                    SELECT
                                       innerQuery.user_chat_group_id 
                                    FROM
                                       user_chat_group_member AS innerQuery 
                                    WHERE
                                       innerQuery.user_id = :userId
                                       AND innerQuery.user_chat_group_id = user_chat_group_member.user_chat_group_id 
                                 )
                              UNION ALL
                              SELECT
                                 user.id,
                                 user.name,
                                 'N/A' AS user_chat_group_id,
                                 user.name AS group_name 
                              FROM
                                 user 
                              WHERE
                                 user.id != :userId
                                 AND NOT EXISTS 
                                 (
                                    SELECT
                                       * 
                                    FROM
                                       (
                                          SELECT
                                             user_chat_group_member.user_chat_group_id,
                                             GROUP_CONCAT(user_chat_group_member.user_id) AS user_id 
                                          FROM
                                             user_chat_group_member 
                                          GROUP BY
                                             user_chat_group_member.user_chat_group_id 
                                          HAVING
                                             FIND_IN_SET(:userId, user_id) > 0 
                                             and count(*) = 2 
                                       )
                                       AS innerQuery 
                                    WHERE
                                       FIND_IN_SET(user.id, innerQuery.user_id) > 0 
                                 )
                           )
                           AS unionedQuery 
                        GROUP BY
                           user_chat_group_id,
                           group_name 
                     )
                     AS x2 
               )
               groupQuery 
               left join
                  (
                     SELECT *
                    FROM message as outerM
                    where 0 = All (
                    select
                        ifnull(sum(case when 
                            innerM.created_at > outerM.created_at 
                            or (innerM.created_at = outerM.created_at and outerM.id < innerM.id) 
                            then 1
                            else 0
                            end),0)
                        from message as innerM
                        where outerM.id != innerM.id and outerM.user_chat_group_id = innerM.user_chat_group_id and outerM.created_at <= innerM.created_at
                    )
                  )
                  latestMessageQuery 
                  on groupQuery.group_id = latestMessageQuery.user_chat_group_id
        `

        return knex.raw(query, {
            userId,
        }).then(([result]) => {
            return result;
        });

    }
}