interface Course {
    name: string;
    description?: string;
}

interface StudentWithCourses {
    name: string,
    gender?: string,
    age: number,
    courses: Array<Course>
}

let studentsWithCourses: Array<StudentWithCourses>;

studentsWithCourses = [{
    name: "Bilal",
    age: 30,
    gender: "Male",
    courses: [{
        name: "oop",
        description: "oop desciption"
    }, {
        name: "DB",
    }]
}, {
    name: "Amna",
    age: 30,
    courses: [{
        name: "oop",
    }]
}];

console.log(studentsWithCourses);
