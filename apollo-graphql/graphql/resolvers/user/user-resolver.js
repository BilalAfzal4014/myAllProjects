const FetchUserCourseUseCase = require("../../../usecases/user-course/fetch-user-course-usecase");
const FetchUserUseCase = require("../../../usecases/user/fetch-user-usecase");
const SaveUserUseCase = require("../../../usecases/user/save-user-usecase");

module.exports = {
    Query: {
        user: (parent, args, context, field) => {
            console.log("Q: Am i consoled after creating user, A: No");
            return FetchUserCourseUseCase.fetchUserWithCourse(args.id);
            /*return {
                id: 1,
                name: "Bilal Afzal",
                age: 21,
                //This will not use courses query(graphQL), but will use User type object below, if we return courses from here as well, then courses will get overwritten by courses function in User type object(if present) below
                courses: [{
                    id: 1,
                    name: "DataBase"
                }]
            }*/
        },
        users: (parent, args, context) => {
            return FetchUserUseCase.fetchAllUsers();
        }
    },
    User: {
        /*courses: (parent, args, context, fields) => {
            console.log("parent", parent)
            console.log("args", args)
            console.log("context", context)
            console.log("fields", fields)
            return [{
                id: 1,
                name: "DataBase-courses-User-Type"
            }]
        }*/
        courses: (user, Arg, context) => {
            console.log("Came in courses", user);
            if (user.courses) { //if parent has already fetched the courses no need to fetch courses again, btw I will overWrite courses if I fetched again
                return user.courses
            }
            return context.dataLoaders.courses.load(user.id);
        },
        department: (user, Arg, context) => {
            console.log("came in department", user);
            //so by console output department and courses run in parallel
            return {
                name: "Dummy-department"
            }
        }
    },
    Mutation: {
        createUser: (parent, arg) => {
            // I will return the user and not call user query to return user
            const user = arg.input;
            return (new SaveUserUseCase(user)).save();
        }
    }
}


