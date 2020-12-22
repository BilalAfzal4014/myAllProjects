const http = require("http");

const middleFile1 = require("./middle-files/middle-file-1");
//just above file will require testClass and will run its code which will console
// "i am been required in two files but i will consoled only just one 0"
//and then
// 1 'testClass'

const middleFile3 = require("./middle-files/middle-file-1");
//we have already require this file in middleFile1 so this will do nothing.

const middleFile2 = require("./middle-files/middle-file-2");
// above file will again require testClass but is is already required and ran in middleFile1, so it will only
//call the function of testClass and console
// 2 'testClass'

const middleMiddleFile1 = require("./middle-of-a-middle-files/middle-middle-file");
//above file will open the file just above it, so that file is already required so nothing will consoled

http.createServer(function(req, res){
    res.write("I am Bilal");
    res.end();
}).listen(3000);

//general rule of remembrance any file which is imported/required once in a project will not take effect on any number of other imports(you know what effect i am talking about)
// so if we really need to run that file again we will make a function and wrap that logic inside a function and call that function