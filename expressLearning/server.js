const http = require("http");

// the below file we are requiring doesn't exporting anything but still this file runs when we require here and will not produce any error
const mysqlConnection = require("./app/databaseConfigurations/db");

//neechay wali line comment kar k kaam chal jaiyeh ga, bcz queue package redis ko pakar laita hai server sy or server per install  honi chaiyeh redis, sath mein additional queue banaty hain tou second parameter bhi redis ka hota hai
const redisClient = require("./app/redisConfig/redisSetup");

const app = require("./app/app");

const normalQueue = require("./app/queueWorker/queueProcesses/normalQueueProcess");
const eventEmitter = require("./app/eventsManager/eventsSetup");
const eventListeners = require("./app/eventsManager/eventListeners/abcListener")(eventEmitter);

const cron = require('node-cron');
const everyMinuteCron = require("./app/cronManager/everyMinCron")(cron);
const tenSecCron = require("./app/cronManager/tenSecCron")(cron);

http.createServer(app).listen(4001);





//// usage of util starts ////
/*const util = require("util");
const events = require("events");

function person(name, gender){
    this.name = name;
    this.gender = gender;
}

util.inherits(person, events.EventEmitter);

let bilal = new person("Bilal", "male");
let minahil = new person("minahil", "female");

let persons = [bilal, minahil];

persons.forEach(function(person){
   person.on("talk", function (data) {
      console.log(`${this.name} is talking and gender is ${this.gender} and talking is ${data}`);
   });
});

bilal.emit("talk", "hello");
minahil.emit("talk", "hi");*/
//// usage of util ends ////


//// usage of stream buffer and pipe starts ////
/*const fs = require("fs");

const readStream = fs.createReadStream("readme.txt", "utf8");
const writeStream = fs.createWriteStream("writeme.txt");

readStream.on("data", function (buffer) {
    writeStream.write(buffer);
});

readStream.on("end", function () {
    writeStream.end();
});

readStream.pipe(writeStream);*/
//// usage of stream buffer ends ////