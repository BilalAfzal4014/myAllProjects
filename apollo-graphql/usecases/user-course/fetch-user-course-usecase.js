const UserCourseRepo = require("../../databases/repositories/userCourseRepo");

module.exports = class FetchUserCourseUseCase {

    static fetchUserWithCourse(userId) {
        return UserCourseRepo.findUserWithCourse(userId);
    }

    static fetchCoursesByUserIds(userIds) {
        return UserCourseRepo.fetchCoursesByUserIds(userIds);
    }

    static fetchUsersByCourseIds(courseIds){
        return UserCourseRepo.fetchUsersByCourseIds(courseIds);
    }
}