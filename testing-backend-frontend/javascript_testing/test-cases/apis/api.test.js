const axios = require("axios");

console.log("api test");


test('test api call on server', function () {

    expect.assertions(1);

    return axios.get('http://localhost:3000')
    //return axios.get('https://jsonplaceholder.typicode.com/users/1')
        .then(function (response) {
            console.log("response", response.data);
            expect(response.data).toEqual({
                status: 200,
                data: {
                    users: [{
                        name: "Bilal",
                        gender: "Male"
                    },{
                        name: "Shahzaib",
                        gender: "Male"
                    }]
                },
                message: ["get user details"]
            });
    });

}, 10000)