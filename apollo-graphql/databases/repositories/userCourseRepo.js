const BaseRepo = require("./baseRepo");
const UserCourseModel = require("../models/userCourseModel");

module.exports = class UserCourseRepo extends BaseRepo {

    static findUserWithCourse(userId) {
        return UserCourseModel.findUserWithCourse([userId])
            .then(([userWithCourse]) => userWithCourse)
    }

    static fetchCoursesByUserIds(userIds){
        return UserCourseModel.fetchCoursesByUserIds(userIds)
    }

    static fetchUsersByCourseIds(courseIds){
        return UserCourseModel.fetchUsersByCourseIds(courseIds)
    }

}
