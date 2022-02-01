const CourseRepo = require("../../databases/repositories/courseRepo");

module.exports = class FetchCourseUseCase{
    static fetchCourse(courseId){
        return CourseRepo.findById(courseId);
    }

    static fetchCourses(){
        return CourseRepo.findAll();
    }
}