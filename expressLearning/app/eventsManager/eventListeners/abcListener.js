module.exports = function (eventEmitter) {
    eventEmitter.on("abc", function (data, cb) {
        console.log("i listened event", data);
        cb({
            name: "Minahil"
        });
    });
};

