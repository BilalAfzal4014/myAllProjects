const FetchUserCourseUseCase = require("../../../usecases/user-course/fetch-user-course-usecase");
const {pushInSameWayAsFirstParamArrayArrived} = require("../../../utils/array-manipulation");

const fetchCoursesOfUsers = (userIds) => {
    //will push courses in same order as get userIds
    return FetchUserCourseUseCase.fetchCoursesByUserIds(userIds)
        .then((usersWithCourses) => {
            return pushInSameWayAsFirstParamArrayArrived(userIds, usersWithCourses, "id", "courses")
        });
}

module.exports = {
    fetchCoursesOfUsers
}