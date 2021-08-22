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

interface DynamicInterface<param = any>{
    justAProp: param;
}

const dynamicInterfaceWithDefaultParam: DynamicInterface = {
    justAProp: "can-be-any-type"
}
console.log(dynamicInterfaceWithDefaultParam);

const dynamicInterfaceWithStringParam: DynamicInterface<string> = {
    justAProp: "string-type"
};
console.log(dynamicInterfaceWithStringParam);

const dynamicInterfaceWithNumberParam: DynamicInterface<number> = {
    justAProp: 1
};
console.log(dynamicInterfaceWithNumberParam);
