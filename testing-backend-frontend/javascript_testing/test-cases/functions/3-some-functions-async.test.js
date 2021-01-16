const someFunctions = require("../../functions/1-some-functions");

test('test async api', function(){
    console.log('test:', 'test async api');
    expect.assertions(1);
    return someFunctions.asyncApi()
        .then((result) => {
            expect(result).toEqual([1, 2, 3]);
        })

}, 7000);