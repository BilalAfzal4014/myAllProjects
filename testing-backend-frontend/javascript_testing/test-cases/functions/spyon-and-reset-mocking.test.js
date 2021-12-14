//const parentModuleObject = require("../../functions/parent-functions");
const {parent} = require("../../functions/parent-functions");
const childModuleObject = require("../../functions/child-functions");
// we need to import as an object not like above to make spy and jest.fn().mockImplementation to work on to work here
//const {child} = require("../../functions/child-functions");

beforeEach(() => {
    jest.resetAllMocks(); // reset mocks to uninitialized state i.e. undefined
    jest.restoreAllMocks(); // reset mocks to un-mocked state i.e. original function for spyon case only
    jest.clearAllMocks(); //to reset the count of called function to zero, remember the case in react where i was inputing on form and expecting api function to not to be called and to be called n times
});

test("Spy a child function", () => {
    let mockedChildFunction = jest.spyOn(childModuleObject, "child");
    mockedChildFunction.mockReturnValue("Mocked child function");

    console.log(parent());
});


test("Is above spyed function available here as well, yes it is", () => {
    console.log(parent());
});


//if we add describe block and its every combination and placed test cases inside them, the condition [un-mocking] will still be the same for both sypedon and jest.fn().mockImplementation case

test("mock a function with jest.fn", () => {
    childModuleObject.child = jest.fn().mockImplementation(() => {
        return "Mocked child function"
    });

    console.log(parent());
    expect(childModuleObject.child).toBeCalledTimes(1);
});

test("check if mocked gets unmocked, nope / check if function called times reset, yes", () => {
    console.log(parent()); //comment this line and see the results for check if function called times reset, yes the function will not be called and value will be zero instead of 1
    expect(childModuleObject.child).toBeCalledTimes(1);
});


//spyedon require to import as an object to mock to work and so does jest.fn().mockImplementation



////////////
/*


describe('1', function(){
    beforeEach(() => {
        jest.resetAllMocks(); // reset mocks to uninitialized state i.e. undefined
        jest.restoreAllMocks(); // reset mocks to un-mocked state i.e. original function for spyon case only
        jest.clearAllMocks(); //to reset the count of called function to zero, remember the case in react where i was inputing on form and expecting api function to not to be called and to be called n times
    });

    test("Spy a child function", () => {
        let mockedChildFunction = jest.spyOn(childModuleObject, "child");
        mockedChildFunction.mockReturnValue("Mocked child function");

        console.log(parent()); //mocked will called
    });
});



test("Is above spyed function available here as well, yes it is", () => {
    console.log(parent()); mocked will called
});


*/
///////////



////////////
/*


describe('1', function(){
    beforeEach(() => {
        jest.resetAllMocks(); // reset mocks to uninitialized state i.e. undefined
        jest.restoreAllMocks(); // reset mocks to un-mocked state i.e. original function for spyon case only
        jest.clearAllMocks(); //to reset the count of called function to zero, remember the case in react where i was inputing on form and expecting api function to not to be called and to be called n times
    });

    test("Spy a child function", () => {
        let mockedChildFunction = jest.spyOn(childModuleObject, "child");
        mockedChildFunction.mockReturnValue("Mocked child function");

        console.log(parent()); //mocked will called
    });

    test("Is above spyed function available here as well, yes it is 1", () => {
        console.log(parent()); original will called
    });
});



test("Is above spyed function available here as well, yes it is 2", () => {
    console.log(parent()); original will called
});


*/
///////////


////////////
/*
describe('1', function(){
    beforeEach(() => {
        jest.resetAllMocks(); // reset mocks to uninitialized state i.e. undefined
        jest.restoreAllMocks(); // reset mocks to un-mocked state i.e. original function for spyon case only
        jest.clearAllMocks(); //to reset the count of called function to zero, remember the case in react where i was inputing on form and expecting api function to not to be called and to be called n times
    });

    test("mock a function with jest.fn 1", () => {
        childModuleObject.child = jest.fn().mockImplementation(() => {
            return "Mocked child function"
        });

        console.log(parent()); //mocked function
    });

});



test("mock a function with jest.fn 2", () => {
    console.log(parent()); //mocked function
});

*/
///////////



////////////
/*
describe('1', function(){

    beforeEach(() => {
        jest.resetAllMocks(); // reset mocks to uninitialized state i.e. undefined
        jest.restoreAllMocks(); // reset mocks to un-mocked state i.e. original function for spyon case only
        jest.clearAllMocks(); //to reset the count of called function to zero, remember the case in react where i was inputing on form and expecting api function to not to be called and to be called n times
    });

    test("mock a function with jest.fn 1", () => {
        childModuleObject.child = jest.fn().mockImplementation(() => {
            return "Mocked child function"
        });

        console.log(parent()); //mocked function
    });

    test("mock a function with jest.fn 2", () => {
        console.log(parent()); //original function
    });

});


test("mock a function with jest.fn 3", () => {
    console.log(parent()); //original function
});

*/
///////////


//pattern for above all four are same, basically function get mocked globally and get unmocked also globally. if there is a describe block and jest.restoreAllMocks is there in beforeEach(inside describe block) then describe block should have atleast two test cases and for original/unmocked version to work in test cases outside this describe block