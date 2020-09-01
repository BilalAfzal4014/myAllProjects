const fs = require("fs");

function willQuerySomething() {

    /*return new Promise(function (resolve, reject) {
        global.connection.query('select id, email from users', function (error, persons, fields) {
            if (error) throw error;

            let currentItr = 0;
            for (let i = 0; i < persons.length; i++) {

                global.connection.query('select message from chat_messages where sender_id =' + persons[i].id, function (error, messages, fields) {
                    if (error) throw error;

                    persons[i].messages = messages;

                    currentItr++;

                    if (currentItr == persons.length) {
                        resolve(persons);
                    }

                });
            }


        });
    });*/


    return new Promise(function (resolve, reject) {
        global.connection.query('select id, email from users', function (error, persons, fields) {
            if (error) throw error;

            let currentItr = 0;
            for (let i = 0; i < persons.length; i++) {
                useAnotherFunction(
                    persons[i].id
                ).then((messages) => {
                    persons[i].messages = messages;
                    currentItr++

                    if (currentItr === persons.length) {
                        resolve(persons);
                    }

                }).catch((err) => {

                });
            }
        });
    });

}

function useAnotherFunction(personId) {
    return new Promise(function (resolve, reject) {

        global.connection.query('select message from chat_messages where sender_id =' + personId, function (error, messages, fields) {
            if (error) throw error;

            resolve(messages);
        });

    });
}

function usingPromiseChaining() {

    return new Promise(function (resolve, reject) {

        global.connection.query('select id, email from users', function (error, persons, fields) {
            if (error) throw error;

            resolve(persons);
        });

    });

}

function promiseChainingContinuation(persons) {

    //we can also use "useAnotherFunction" function in for loop see above technique but we will use separate technique below

    let promiseArr = [];

    for (let i = 0; i < persons.length; i++) {

        promiseArr.push(new Promise(function (resolve, reject) {
            global.connection.query('select message from chat_messages where sender_id =' + persons[i].id, function (error, messages, fields) {
                if (error) throw error;
                persons[i].messages = messages;

                //see results in console
                resolve({status: "resolved", data: {messages}});

                //reject({status: "reject", data: {messages}});
            });
        }));
    }

    return new Promise((resolve, reject) => {
        Promise.all(promiseArr.map((p) => {
                return p.catch(e => e)
            })
        ).then((results) => {
            //we will do for loop to check the status of all promises, but for now we are just sending persons with resolve
            resolve(persons);
        }).catch((error) => {

        });
    });

};

const downloadFile = () => {
    const file = 'app/uploads/videos/The.Siege.Of.Jadotville.2016.720p.WEBRip.x264-[YTS.LT].mp4';
    let createStream = fs.createReadStream(file);
    return createStream;
};

const downloadFileSendRes = (res) => {
    const file = 'app/uploads/videos/The.Siege.Of.Jadotville.2016.720p.WEBRip.x264-[YTS.LT].mp4';

    let createStream = fs.createReadStream(file);

    /*createStream.on("data", function(buffer) {
        res.write(buffer);
    });
    createStream.on("end", function() {
        res.end();
    });*/

    createStream.pipe(res);

    //res.download(file);

    //all of above techniques will work
};


module.exports = {
    willQuerySomething,
    usingPromiseChaining,
    promiseChainingContinuation,
    downloadFile,
    downloadFileSendRes
};


/*
    in place of callback we can use async-await, if the callback contains the whole below body
    if the callback don't contains the whole body you can make the separate function which contains
    what the callback function contains in its body and move that code into that function and then use aync-await
    in the place of callback

 */