const FetchUserCourseUseCase = require("../../../usecases/user-course/fetch-user-course-usecase");
const {pushInSameWayAsFirstParamArrayArrived} = require("../../../utils/array-manipulation");

const fetchUsersOfCourses = (courseIds) => {
    return FetchUserCourseUseCase.fetchUsersByCourseIds(courseIds)
        .then((usersWithCourses) => {
            return pushInSameWayAsFirstParamArrayArrived(courseIds, usersWithCourses, "id", "users")
        });
}

module.exports = {
    fetchUsersOfCourses
}