let normalQueue = require("../queueConfigurations").normalQueue;

//console.log("asdasd");

normalQueue.process(function (job, done) {
    console.log("job", job.data);
    job.progress(42);
    done();
});

module.exports = normalQueue;
//module.exports = {};


// if uncomment the commented code then it will still work, the reason is when we export this module in other file then upon require the whole file will run