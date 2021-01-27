//const SoundPlayer = require("../../functions/5-some-class");
const SoundPlayerConsumer = require("../../functions/4-some-class");

jest.mock("../../functions/5-some-class");

/*
const mockPlaySoundFile = jest.fn().mockImplementation(function () {
    return "mocking method factory mock";
});
jest.mock("../../functions/5-some-class", () => {
    return jest.fn().mockImplementation(() => {
        return {playSoundFile: mockPlaySoundFile};
    });
});
*/

//OR


/*const mockPlaySoundFile = jest.fn().mockImplementation(function () {
    return "mocking method factory mock 1.1";
});
jest.mock("../../functions/5-some-class", () => {
    return jest.fn().mockImplementation(function(){
        this.playSoundFile = mockPlaySoundFile
    });
});*/



//If the class is not the default export from the module then you need to return an object with the key that is the same as the class export name.

/*import {SoundPlayer} from './sound-player';
jest.mock('./sound-player', () => {
    // Works and lets you check for constructor calls:
    return {
        SoundPlayer: jest.fn().mockImplementation(() => {
            return {playSoundFile: () => {}};
        }),
    };
});*/



beforeEach(() => {
    // Clear all instances and calls to constructor and all methods:
    //SoundPlayer.mockClear();
    //mockPlaySoundFile.mockClear();
});


test("4-mock-class 1", function () {

    let obj1 = new SoundPlayerConsumer();
    console.log("result", obj1.playSomethingCool());

});

/*
run your tests in sequence

https://stackoverflow.com/questions/47291189/is-there-a-way-to-run-some-tests-sequentially-with-jest
https://stackoverflow.com/questions/32751695/how-to-run-jest-tests-sequentially

 */