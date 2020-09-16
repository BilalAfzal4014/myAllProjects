const express = require("express");
const router = express.Router();
const fs = require("fs");
const multer = require('multer');
//const upload = multer({dest: 'app/uploads/'});
let normalQueue = require("../queueWorker/queueConfigurations").normalQueue;
let emailTransporter = require("../emailConfigurations/emailSetup");
let eventEmitter = require("../eventsManager/eventsSetup");
let customMiddleware = require("../middlewares/customMiddleware");
const userServices = require("../services/userServices");
const axios = require("axios");
const request = require('request');
const FormData = require('form-data');

console.log("i will run only once when the server will run and that too without hitting url");
let count = 0;

router.get("/", customMiddleware({name: "Bilal"}), function (req, res, next) {

    normalQueue.add({
        date: new Date(),
        message: "hasta la vista baby"
    });

    count++;
    res.status(200).json({
        status: true,
        data: count,
        message: "response-1 comes successfully"
    });
});

router.get("/email", function (req, res, next) {

    let mailOptions = {
        from: 'bilalafzal4014@gmail.com',
        to: 'majidashraf81@gmail.com', //comma separated emails for multiple clients
        subject: 'Sending Email using Node.js',
        text: 'That was easy!'
    };

    emailTransporter.sendMail(mailOptions, function (error, info) {

        let response;

        if (error) {
            response = error;
        } else {
            response = info.response;
        }

        res.status(200).json({
            status: true,
            data: response,
            message: "response-2 comes successfully"
        });
    });

});

router.get("/event", function (req, res, next) {

    eventEmitter.emit("abc", {
        name: "Bilal"
    }, function (callbackParam) {
        console.log("in callback", callbackParam);
    });

    res.status(200).json({
        status: true,
        data: {},
        message: "response-3 comes successfully"
    });

});

/*router.post("/file-upload", upload.single('image'), function (req, res, next) {

    console.clear();
    console.log(req.file);

    res.status(200).json({
        status: true,
        data: req.body,
        message: "response-4 comes successfully"
    });

});*/

router.post("/file-upload", function (req, res, next) {

    const upload = multer({dest: 'app/uploads/images'}).single('image');

    upload(req, res, function (err) {
        if (err instanceof multer.MulterError) {
            console.log("error 1", err);
            // A Multer error occurred when uploading.
        } else if (err) {
            console.log("error 2", err);
            // An unknown error occurred when uploading.
        }

        //every thing works fine here
        res.status(200).json({
            status: true,
            data: {},
            message: "response-4 comes successfully"
        });
    });

});

router.post("/file-upload-axios", function (req, res) {

    const storage = multer.memoryStorage();
    const upload = multer({storage: storage}).single('image');

    upload(req, res, function (err) {
        if (err) {
            return res.status(400).json({
                code: 400,
                status: true,
                data: {},
                message: "There was an error",
                error: err,
            });
        }

        if (req.file.buffer === undefined) {
            res.status(200).json({
                code: 400,
                status: "Error",
                data: {},
                message: "You probably didn't choose the file"
            });
        }

        //console.log(req.body.name);
        //return;

        let form = new FormData();
        form.append("image", req.file.buffer, req.file.originalname);

        axios.post(
            "http://localhost:4001/file-upload",
            form,
            {
                headers: {
                    'Content-Type': `multipart/form-data; boundary=${form._boundary}`
                }
            },
        ).then((response) => {
            return res.status(200).json({
                code: 200,
                status: "success",
                data: {},
                message: "File uploaded successfully"
            });
        }).catch((...error) => {

        });

    });

});

router.get("/get-file", function (req, res, next) {
    //const file = 'app/uploads/videos/sample.mp4';
    const file = 'app/uploads/videos/The.Siege.Of.Jadotville.2016.720p.WEBRip.x264-[YTS.LT].mp4';
    res.download(file); // Set disposition and send it.
});

router.get("/get-html", function (req, res, next) {
    //console.log({ root: __dirname });
    //console.log(__dirname + '/app/views/index.html');
    res.sendFile("/var/www/html/expressLearning/app/views/index.html");
});

/*router.get("/play-video/:videoName", function (req, res, next) {

    const path = 'app/uploads/videos/' + req.params.videoName;
    const stat = fs.statSync(path);
    const fileSize = stat.size;
    const range = req.headers.range;
    if (range) {
        const parts = range.replace(/bytes=/, "").split("-");
        const start = parseInt(parts[0], 10);
        const end = parts[1]
            ? parseInt(parts[1], 10)
            : fileSize - 1;
        const chunksize = (end - start) + 1;
        const file = fs.createReadStream(path, {start, end});
        const head = {
            'Content-Range': `bytes ${start}-${end}/${fileSize}`,
            'Accept-Ranges': 'bytes',
            'Content-Length': chunksize,
            'Content-Type': 'video/mp4',
        };
        res.writeHead(206, head);
        file.pipe(res);
    } else {
        const head = {
            'Content-Length': fileSize,
            'Content-Type': 'video/mp4',
        };
        res.writeHead(200, head);
        fs.createReadStream(path).pipe(res)
    }

});*/

router.get("/play-video/:videoName", function (req, res, next) {

    var contentType = "text/html";
    let fn = 'app/uploads/videos/' + req.params.videoName;
    fs.stat(fn, function (err, stats) {
        var headers;

        if (err) {
            res.writeHead(404, {"Content-Type": "text/plain"});
            res.end("Could not read file");
            return;
        }

        var range = req.headers.range || "";
        var total = stats.size;

        if (range) {

            var parts = range.replace(/bytes=/, "").split("-");
            var partialstart = parts[0];
            var partialend = parts[1];

            var start = parseInt(partialstart, 10);
            var end = partialend ? parseInt(partialend, 10) : total - 1;

            var chunksize = (end - start) + 1;

            headers = {
                "Content-Range": "bytes " + start + "-" + end + "/" + total,
                "Accept-Ranges": "bytes",
                "Content-Length": chunksize,
                "Content-Type": contentType
            };
            res.writeHead(206, headers);

        } else {

            headers = {
                "Accept-Ranges": "bytes",
                "Content-Length": stats.size,
                "Content-Type": contentType
            };
            res.writeHead(200, headers);

        }

        var readStream = fs.createReadStream(fn, {start: start, end: end});
        readStream.pipe(res);

    });


});

router.get("/query-something", function (req, res) {

    /*userServices.willQuerySomething().then((response) => {
        res.status(200).json({
            status: "success",
            data: response,
            message: "user messages"
        });
    }).catch((err) => {

    });*/

    userServices.usingPromiseChaining().then((persons) => {
        return userServices.promiseChainingContinuation(persons);
    }).then((persons) => {
        res.status(200).json({
            status: "success",
            data: persons,
            message: "user messages"
        });
    });

});

router.get("/download-from-some-where", function (req, res) {
    axios({
        method: 'get',
        url: 'http://bit.ly/2mTM3nY',
        responseType: 'stream'
    }).then((response) => {
        //below statement will create a jpg file and download image content on it
        //response.data.pipe(fs.createWriteStream('ada_lovelace.jpg'))
        response.data.pipe(res);
    }).catch((...error) => {

    });

});


router.get("/download-from-some-where-1", function (req, res) {
    axios({
        method: 'get',
        url: 'http://bit.ly/2mTM3nY',
        responseType: 'stream'
    }).then((response) => {
        let bufferCollector = [];

        response.data.on("data", function (buffer) {
            bufferCollector.push(buffer);
        });

        response.data.on("end", function () {
            let fileBuffer = Buffer.concat(bufferCollector);
            res.write(fileBuffer);
            res.end();
        });
    }).catch((...error) => {

    });

});


router.get("/download-movie-some-where", function (req, res) {
    axios({
        method: 'get',
        url: 'http://localhost:4001/get-file',
        responseType: 'stream'
    }).then((response) => {
        response.data.pipe(res);
    }).catch((...error) => {

    });

});

router.get("/download-movie-some-where-pipe", function (req, res) {
    axios({
        method: 'get',
        url: 'http://localhost:4001/get-file-pipe',
        responseType: 'stream'
    }).then((response) => {
        response.data.pipe(res);
    }).catch((...error) => {

    });

});

router.get("/get-file-pipe", function (req, res, next) {
    const file = 'app/uploads/videos/The.Siege.Of.Jadotville.2016.720p.WEBRip.x264-[YTS.LT].mp4';
    const readStream = fs.createReadStream(file);
    readStream.pipe(res);
});

router.get("/download-csv-without-saving-on-server", function (req, res) {
    res.setHeader('Content-disposition', 'attachment; filename=testing.csv');
    res.set('Content-Type', 'text/csv');
    res.status(200).send('a,b,c\n1,2,3');
});

router.get("/download-csv-without-saving-on-server-with-chunks", function (req, res) {
    res.setHeader('Content-disposition', 'attachment; filename=testing.csv');
    res.set('Content-Type', 'text/csv');

    let counter = 0;
    for (let i = 0; i < 90000; i++) {
        let str = "";
        for (let j = 0; j < 100; j++) {
            str += `a${j + counter},b${j + counter},c${j + counter}\n`;
            //res.write(str);
        }
        res.write(str);
        counter += 100;
    }
    //res.write(str);
    res.end();

});


router.get("/download-csv-without-saving-on-server-with-chunks-for-axios", function (req, res) {

    let counter = 0;
    for (let i = 0; i < 90000; i++) {
        let str = "";
        for (let j = 0; j < 100; j++) {
            str += `a${j + counter},b${j + counter},c${j + counter}\n`;
        }
        res.write(str);
        counter += 100;
    }
    res.end();

});


router.get("/download-csv-without-saving-on-server-with-chunks-with-axios", function (req, res) {
    res.setHeader('Content-disposition', 'attachment; filename=testing.csv');
    res.set('Content-Type', 'text/csv');

    axios({
        method: 'get',
        url: 'http://localhost:4001/download-csv-without-saving-on-server-with-chunks-for-axios',
        responseType: 'stream'
    }).then((response) => {
        response.data.pipe(res);
    }).catch((...error) => {

    });

});


router.get("/download-file-from-other-file", function (req, res) {
    res.setHeader('Content-disposition', 'attachment; filename=movie.mp4');
    res.set('Content-Type', 'media');
    let stream = userServices.downloadFile();

    /*stream.on("data", function(buffer) {
        res.write(buffer);
    });
    stream.on("end", function() {
        res.end();
    });*/

    stream.pipe(res);

    //all of above techniques will work
});


router.get("/download-file-send-res", function (req, res) {
    userServices.downloadFileSendRes(res);
});

router.post("/temp-file-upload", function (req, res) {

    let target = request.post("http://localhost:4001/actual-upload-for-temp-file", function(error, resp, body){
        //5
        if(!error){
            body = JSON.parse(body);
            return res.status(body.status).json(body);
        }
    });
    req.pipe(target);

    target.on('response', function (response) {
        //2
        //console.log("Res", response.statusCode);
        response.on('data', function (data) {
            //3
            // compressed data as it is received
            //console.log(data);
            //console.log('received ' + data.length + ' bytes of compressed data')
        })
    });

    target.on('data', function (data) {
        //1
        //console.log("Res", data);

    });

    target.on('end', function () {
        //4
        //console.log('All done!');
        //send the response or make a completed callback here...
    });

    target.on('finish', function () {
        //6
        //console.log('All done!');
        //send the response or make a completed callback here...
    });

});

router.post("/actual-upload-for-temp-file", function (req, res) {

    const writeStream = fs.createWriteStream("testing.txt");

    req.on("data", function (buffer) {
        writeStream.write(buffer);
    });

    req.on("end", function () {
        writeStream.end();
        return res.status(200).json({
            status: 200,
            data: {},
            message: "File uploaded successfully actual"
        });
    });

});

module.exports = router;