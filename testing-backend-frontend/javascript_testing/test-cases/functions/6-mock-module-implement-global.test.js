jest.mock("../../functions/3-some-functions")

const someFunctions = require("../../functions/1-some-functions");
const someFunctions3 = require("../../functions/3-some-functions");



test("test global implementation of a mocked function", function(){

  //console.log("somefunc", someFunctions3.divide(6, 3));
  console.log("somefunc", someFunctions.sumAndDivide(3, 6, 3));

  expect(true).toBeTruthy();

});