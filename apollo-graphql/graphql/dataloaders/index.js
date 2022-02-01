const DataLoader = require('dataloader');
const {fetchCoursesOfUsers} = require("./courses/courses-dataloader");
const {fetchUsersOfCourses} = require("./users/users-dataloader");

function attachDataLoadersWithContext() {
    return {
        dataLoaders: {
            courses: new DataLoader(async (userIds) => {
                // the size of returned array should be equal to size of parameter array which is userIds in this case
                return fetchCoursesOfUsers(userIds);
            }),
            users: new DataLoader(async (courseIds) => {
                return fetchUsersOfCourses(courseIds);
            }),
        }
    }
}


module.exports = attachDataLoadersWithContext;