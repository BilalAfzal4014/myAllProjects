const someFunctions = require("../../functions/1-some-functions");

global.some = "some"
//order of execution of describe depends upon where it is written
// order of execution is of code in between two or more files is random but can depends upon which file is touched lately i.e. touched file code will execute first
// before and after hooks are not global but are on file level
//to run test cases of a specific file only: npm run test -- api.test.js

console.log("some function preemtive test file");

beforeAll(function () {
    console.log("before all called for some functions preemtive");
});

afterAll(function () {
    console.log("after all called for some functions preemtive");
});


beforeEach(function () {
    console.log("before each called for some functions preemtive");
});

afterEach(function () {
    console.log("after each called for some functions preemtive");
});


describe('some function preemtive describe scope', function () {

    console.log("some function preemtive describe scope");

    beforeAll(function () {
        console.log("before all called for describe preemtive");
    });

    afterAll(function () {
        console.log("after all called for describe preemtive");
    });


    beforeEach(function () {
        console.log("before each called for describe preemtive");
    });

    afterEach(function () {
        console.log("after each called for describe preemtive");
    });

    test('test concat bilal and afzal', function () {
        console.log("test:", "test concat bilal and afzal");
        expect(someFunctions.sum('bilal', 'afzal')).toBe('bilalafzal');
    });

    test('test concat shahzaib and afzal', function () {
        console.log("test:", "test concat shahzaib and afzal");
        expect(someFunctions.sum('shahzaib', 'afzal')).not.toBe('bilalafzal');
    });

});


test('test sum with params 2 and 3', function () {

    console.log("test:", "test sum with params 2 and 3");
    // toBe for only pre-emtive values
    // toEqual for both pre-emtive and non pre-emtive values
    // can also write more than one expect in single test callback function
    // other/last parameter of all of them is timeout value in milliseconds

    expect(someFunctions.sum(2, 3)).toBe(5);

    //expect(someFunctions.sum(2, 3)).toEqual(5);
});


test('test sum with params 4 and 9', function () {
    console.log("test:", "test sum with params 4 and 9");
    expect(someFunctions.sum(4, 9)).not.toBe(5);
});



