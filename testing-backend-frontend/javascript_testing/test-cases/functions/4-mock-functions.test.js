const someFunctions = require("../../functions/1-some-functions");
const someFunctions2 = require("../../functions/2-some-functions");

test("test mocking of a callback function", function () {

    const mockCallback = jest.fn(function (x) {
        return 42 + x
    });


    let newArr = [0, 1].map(mockCallback);

// The mock function is called twice
    expect(mockCallback.mock.calls.length).toBe(2);

// The first argument of the first call to the function was 0
    expect(mockCallback.mock.calls[0][0]).toBe(0);

// The first argument of the second call to the function was 1
    expect(mockCallback.mock.calls[1][0]).toBe(1);

// The return value of the first call to the function was 42
    expect(mockCallback.mock.results[0].value).toBe(42);

    console.log("new Array", newArr);
    console.log("length", mockCallback.mock.calls.length);
    console.log("mock object", mockCallback.mock);
    console.log("call object", mockCallback.mock.calls);
    console.log("results object", mockCallback.mock.results);

});


test("test mocking of a simple function", function () {


    let mockConcat = jest.fn(someFunctions.concat);


    //mockConcat function will be calling not someFunctions.concat, but we can't tell the diff now since both have same implementation

    expect(mockConcat('Bilal', 'Afzal')).toBe("BilalAfzal");

});


test("test mock implementation of a simple function", function () {

    // to create a copy of a function
    let mockConcat = jest.fn(someFunctions.concat);

    /*someFunctions.concat = jest.fn(someFunctions.concat);
    someFunctions.concat.mockImplementation((a, b) => {
        console.log(`The passed parameters are ${a} ${b}`);
        return `${a} ${b}`;
    })*/

    //to override its implementation
    mockConcat.mockImplementation((a, b) => {
        console.log(`The passed parameters are ${a} ${b}`);
        return `${a} ${b}`;
    })

    console.log("mockConcat1", someFunctions.concat('Bilal', 'Afzal')); // will call the original function
    console.log("mockConcat2", mockConcat('Bilal', 'Afzal'));
    expect(mockConcat('Bilal', 'Afzal')).toBe("Bilal Afzal");
});


test("test mock implementation of a simple inner function from a different file with spyOn", function () {

    //if someFunctions.concat is mocked in any above test, then below line will also called a mocked function
    //console.log("mockConcat3", someFunctions.concat("A", "B"));

    //check if the parent function will call the child mocked function - true
    //check if the mocked function will also be available on other file - false

    //spyOn and mock a function and assign to that same function or different variable will be available only in the same file scope
    // parent will only child mocked or spy function if child function is written in else where file from parent

    let mockAppendGender = jest.spyOn(someFunctions2, "appendGender");

    mockAppendGender.mockReturnValue("I am fake returned value");

    console.log("spyOn", someFunctions.arrangeTheName("Bilal", "Afzal"));

    expect(someFunctions.arrangeTheName("Bilal", "Afzal")).toBe("I am fake returned value");

    mockAppendGender.mockRestore();
});

// see in documentation https://jestjs.io/docs/en/mock-functions


/*
function mocked with different variable will be on one test level only
function mocked with the same variable i.e fun = jest.fn(fun) will be on the file level only

A parent will call a mocked child function, if a child function is written in different file


describe blocks run sequential, but all the tests in the inside a describe block run parallel

*/