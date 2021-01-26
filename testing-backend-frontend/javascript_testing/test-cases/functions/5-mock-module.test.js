const someFunctions = require("../../functions/1-some-functions");
//const someFunctions2 = require("../../functions/2-some-functions");

// when call by the parent function then the mocked function will be called
jest.mock("../../functions/2-some-functions", () => {
    return {
        appendGender: jest.fn(function (name, gender) {
            return `I am ${name} and my gender is ${gender}`;
        }),
        /*appendGender: jest.fn().mockImplementation(function(name, gender){
            //this will also do the same thing
            return `I am ${name} and my gender is ${gender}`;
        })*/
    }
});

// preference will be given to module factory mock vs __mocks__ folder

describe("mocking a module", function () {

    //afterEach(function () {
    //jest.unmock("../../functions/2-some-functions");
    //jest.resetAllMocks();
    //jest.restoreAllMocks();
    //});

    test("test-1 to mock a module", function () {


        /*const getFromFunction = () => require("../../functions/1-some-functions");
        const someFunctions = getFromFunction();*/

        // if we require above file and then mock inside the test the require and mock will happen at different levels
        // we would not be able to see the mocked results


        /*jest.mock("../../functions/2-some-functions"/!*, () => {
            return {
                appendGender: jest.fn(function (name, gender) {
                    return `I am ${name} and my gender is ${gender}`;
                })
            }
        }*!/);

        const getFromFunction = () => require("../../functions/1-some-functions");


        const someFunctions = getFromFunction();*/

        /*
          const getFromFunction2 = () => require("../../functions/2-some-functions");
          const someFunctions2 = getFromFunction2();

          someFunctions2.appendGender.mockImplementation(function (name, gender) {
              return `I am ${name} and my gender is ${gender}`;
          });
      */
        console.log("test-1 to mock a module", someFunctions.arrangeTheName("Muhammad Bilal", "Afzal"));

        expect(someFunctions.arrangeTheName("Bilal", "Afzal")).toBe('I am Bilal Afzal and my gender is Male');


        //delete require.cache[require.resolve('../../functions/1-some-functions')]; //delete a module from cache

        //jest.unmock("../../functions/2-some-functions");

    });

});

test("test-2 to mock a module", function () {


    /*
    const getFromFunction = () => require("../../functions/1-some-functions");
    const someFunctions = getFromFunction();
    */

    /*const getFromFunction2 = () => require("../../functions/2-some-functions");
    const someFunctions2 = getFromFunction2();*/


    console.log("test-2 to mock a module", someFunctions.arrangeTheName("Muhammad Bilal", "Afzal"));

    expect(someFunctions.arrangeTheName("Bilal", "Afzal")).toBe('I am Bilal Afzal and my gender is Male');


});


/*beforeEach(function(){
    //jest.unmock("../../functions/2-some-functions");
    // will unMock for the above test, but still not invoke the original function and will get undefined instead
    // because the function is still mocked with default configuration
    //jest.resetModules();
    //jest.isMockFunction(someFunctions2.appendGender)
})*/


/*afterEach(function () {
    //
    //jest.unmock("../../functions/2-some-functions");
    //jest.resetAllMocks();
    jest.restoreAllMocks();
});*/

/*
Important Notes
mocking a module and require a module must be on same level otherwise it will not work
if we mock a module inside some test file then
it is almost impossible to unmock it
all the function in the module will be mocked, but needs to override implementation otherwise will return undefined
no matter if we mock a module inside test or describe, it will be still available in all test cases
it's better to write a different test file if we don't need mocked functions there, bcz mocking a module on one test file will be for that file only
 */