const FetchCourseUseCase = require("../../../usecases/course/fetch-course-usecase");

module.exports = {
    Query: {
        course: (parent, arg, context, fields) => {
            return FetchCourseUseCase.fetchCourse(arg.id);
        },
        courses: (parent, arg, context, fields) => {
            return FetchCourseUseCase.fetchCourses();
        }
    },
    Course: {
        users: (course, arg, context) => {
            return context.dataLoaders.users.load(course.id);
        }
    }
}