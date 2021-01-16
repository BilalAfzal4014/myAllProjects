const someFunctions = require("../../functions/1-some-functions");

console.log("global1", global.some); // is set on file 1 will be undefined here
console.log("global2", global.something); // is set on some functions file and we are requiring here
console.log("global3", global.server); // is set with nodemon and will not accessible here because this piece of code is running with jest

console.log("some function non preemtive test file");

beforeAll(function () {
    console.log("before all called for some functions non preemtive");
});

afterAll(function () {
    console.log("after all called for some functions non preemtive");
});


beforeEach(function () {
    console.log("before each called for some functions non preemtive");
});

afterEach(function () {
    console.log("after each called for some functions non preemtive");
});



test('test get person', function () {

    console.log("test:", "test get person");
    expect(someFunctions.getPerson()).toEqual({
        name: "Bilal",
        gender: "Male"
    });

});


test('test get person with false negative', function () {
    console.log("test:", "test get person with false positive");
    // order of object properties doesn't matter
    expect(someFunctions.getPerson()).toEqual({
        gender: "Male",
        name: "Bilal",
    });


    expect(someFunctions.getPerson()).not.toEqual({
        name: "Shahzaib",
        gender: "Male",
    });
});

describe('some function non preemtive describe scope', function () {

    console.log("some function non preemtive describe scope");


    beforeAll(function () {
        console.log("before all called for describe non preemtive");
    });

    afterAll(function () {
        console.log("after all called for describe non preemtive");
    });


    beforeEach(function () {
        console.log("before each called for describe non preemtive");
    });

    afterEach(function () {
        console.log("after each called for describe non preemtive");
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

