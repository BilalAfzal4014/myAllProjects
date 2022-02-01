const UserResolver = require("./user/user-resolver");
const CourseResolver = require("./course/course-resolver");
const resolvers = [UserResolver, CourseResolver];

module.exports = {resolvers};