const BaseModel = require("./baseModel");
const {knex} = require("../sql-connection");

module.exports = class UserCourseModel extends BaseModel {
    static get tableName() {
        return "user_course_mapping";
    }

    static findUserWithCourse(userIds) {
        const query = `
            select user.id, user.name, user.age, user.is_deleted, user.created_at, user.updated_at,
            concat('[', group_concat(json_object('id', course.id, 'name', course.name, 'is_deleted', course.is_deleted,
            'created_at', course.created_at, 'updated_at', course.updated_at)), ']') as courses
            from user left join user_course_mapping on user.id = user_course_mapping.user_id
            left join course on user_course_mapping.course_id = course.id
            where user.id in (:userIds)
            group by user.id, user.name, user.age, user.is_deleted, user.created_at, user.updated_at
        `;

        return knex.raw(query, {userIds}).then(([users]) => {
            for (let user of users) {
                user.courses = JSON.parse(user.courses);
                if (user.courses.length === 1 && !user.courses[0].id) {
                    user.courses = [];
                }
            }
            return users;
        });
    }

    static fetchCoursesByUserIds(userIds) {
        // we can skip user table join here but any ways query was written already
        return UserCourseModel.findUserWithCourse(userIds)
    }

    static fetchUsersByCourseIds(courseIds) {
        const query = `
            select course.id, course.name, concat('[', group_concat(json_object('id', user.id, 'name', user.name, 'is_deleted', user.is_deleted,
            'age', user.age)), ']') as users
            from course left join user_course_mapping on course.id = user_course_mapping.course_id
            left join user on user_course_mapping.user_id = user.id
            where course.id in (:courseIds)
            group by course.id, course.name
        `;

        return knex.raw(query, {courseIds}).then(([courses]) => {
            for (let course of courses) {
                course.users = JSON.parse(course.users);
                if (course.users.length === 1 && !course.users[0].id) {
                    course.users = [];
                }
            }
            return courses;
        });
    }
}
