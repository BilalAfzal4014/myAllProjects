const someFunctions = require("../../functions/1-some-functions");

test('test async api', function () {
    console.log('test:', 'test async api');
    //expect.assertions(1); // assertions function and passed number is just to know before-hand that how many expect function will be called, that is usually happens in async scenario
    return someFunctions.asyncApi()
        .then((result) => {
            expect(result).toEqual([1, 2, 3]);
        })

}, 7000);


test('test async callback', function (done) {
    console.log('test:', 'test async callback');

    someFunctions.asyncApiCallback(function (result) {
        try {
            expect(result).toEqual([1, 2, 3]);
            done();
        } catch (e) {
            done(e);
        }
    });

}, 7000);