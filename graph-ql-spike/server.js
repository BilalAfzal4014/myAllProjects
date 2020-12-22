const http = require("http");
const app = require("./app/app");

http.createServer(app).listen(5000, function(){
    console.log("i am listening");
})

/*
we can also listen by only express module bcz it is using http module inside of it
const express = require("express");

const app = express();

app.listen(5000, () => {
    console.log("i am here")
})*/


/*
import with extension
import  tar, * as abc from "./app/del.js"
// or
//import tar, {foo, bar} from "./app/del.js"

abc.foo();
abc.bar();
tar();
*/
