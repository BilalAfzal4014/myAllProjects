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

interface DynamicInterface<param = any> {
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


interface InterfaceAsAFunction {
    (key: number, value: string): void;
}

const interfaceFunctionDeclaration: InterfaceAsAFunction = function (age, name: string)/*can write type with function arg but it doesn't matter*/: void /*can write return type as well but it doesn't matter*/ {
    console.log(age);
    console.log(name);
}
//interfaceFunctionDeclaration("30", "Bilal"); // this will produce error
interfaceFunctionDeclaration(30, "Bilal");


interface InterfaceAsAArrayType {
    [index: number]: number
}

let numArr: InterfaceAsAArrayType = [1, 2, 3];
console.log(numArr);

interface InterfaceAsAObjectType { //adding dynamic properties
    [index: string]: string
}

let strArr: InterfaceAsAObjectType = {}; //assigment is must here
strArr.TS = "TypeScript";
strArr["JS"] = "JavaScript";
console.log(strArr);




interface readOnlyInterface {
    name: string;
    readonly SSN: number;
}

let personObj: readOnlyInterface  = { SSN: 110555444, name: 'James Bond' }

personObj.name = 'Steve Smith'; // OK
//personObj.SSN = '333666888'; // Compiler Error


