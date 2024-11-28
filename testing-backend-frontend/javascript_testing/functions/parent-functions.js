//let {child} = require("./child-functions");
// we need to import as an object not like above to make spy and jest.fn().mockImplementation to work on to work here
const childModuleObject = require("./child-functions");

function parent(){
    //return `Parent ${child()}`; // this will not get mocked for both spy and jest.fn().mockImplementation case


    return `Parent ${childModuleObject.child()}`;
    //return `Parent ${child()}`;

    //both above and below code will be spyedon and jest.fn().mockImplementation

    /*const {child} = childModuleObject;
    return `Parent ${child()}`;*/
}

module.exports = {
    parent
};