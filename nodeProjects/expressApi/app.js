const express = require('express');
const app = express();
const bodyParser = require('body-parser');
//const mysql = require('mysql')
//const db = require('./db');
const loginRoutes = require('./apis/routes/login');
const userRoutes = require('./apis/routes/user');
const chatRoutes = require('./apis/routes/chat');
// parse the request body data to json
app.use(bodyParser.urlencoded({extended: false}));
app.use(bodyParser.json());


// allow acess of api hit by browsers

app.use((req, res, next) => {
  res.header("Access-Control-Allow-Origin", "*"); //allowed for rest api
  res.header(
    "Access-Control-Allow-Headers", "*" // allowed for everything in headers
    //"Origin, X-Requested-With, Content-Type, Accept, id, userId, Authorization"
  	//"Origin, X-Requested-With, Content-Type, Accept, Authorization"
  );
  if (req.method === 'OPTIONS') {
      res.header('Access-Control-Allow-Methods', 'PUT, POST, PATCH, DELETE, GET');
      return res.status(200).json({});
  }
  next();
});



app.use('/', loginRoutes);
app.use('/user', userRoutes);
app.use('/chat', chatRoutes);


// if url don't match above middlewares
app.use(function(req, res, next){
	const error = new Error("Not Found");
	error.status = 404;
	next(error);
});

app.use(function(error, req, res, next){
	res.status(error.status || 500).json({
		error:{
			message: error.message
		}	
	});
});


module.exports = app;