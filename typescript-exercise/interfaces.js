var studentsWithCourses;
studentsWithCourses = [{
        name: "Bilal",
        age: 30,
        gender: "Male",
        courses: [{
                name: "oop",
                description: "oop desciption"
            }, {
                name: "DB"
            }]
    }, {
        name: "Amna",
        age: 30,
        courses: [{
                name: "oop"
            }]
    }];
console.log(studentsWithCourses);
var dynamicInterfaceWithDefaultParam = {
    justAProp: "can-be-any-type"
};
console.log(dynamicInterfaceWithDefaultParam);
var dynamicInterfaceWithStringParam = {
    justAProp: "string-type"
};
console.log(dynamicInterfaceWithStringParam);
var dynamicInterfaceWithNumberParam = {
    justAProp: 1
};
console.log(dynamicInterfaceWithNumberParam);
var interfaceFunctionDeclaration = function (age, name) {
    console.log(age);
    console.log(name);
};
//interfaceFunctionDeclaration("30", "Bilal"); // this will produce error
interfaceFunctionDeclaration(30, "Bilal");
var numArr = [1, 2, 3];
console.log(numArr);
var strArr = {};
strArr.TS = "TypeScript";
strArr["JS"] = "JavaScript";
console.log(strArr);
var personObj = { SSN: 110555444, name: 'James Bond' };
personObj.name = 'Steve Smith'; // OK
//personObj.SSN = '333666888'; // Compiler Error
