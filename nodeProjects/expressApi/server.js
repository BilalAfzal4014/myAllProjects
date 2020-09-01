const http = require('http');
const socket = require('socket.io');
const app = require('./app');
const db = require('./db');
const CircularJSON = require('circular-json');


const port = process.env.PORT || 3000;

const server = http.createServer(app);

server.listen(port);


const io = socket(server);

io.on('connection', function(socket){
	socket.on('login', function(data){
		
		if(data != null){
			socket.myAuthId = data.id;
			socket.myUserId = data.userId;
			socket.myAuthorization = data.Authorization;
		
			var queryStr = 'INSERT INTO sockets (auth_id, user_id, socketObj) VALUES ( ' + data.id + ',' + data.userId + ',' + "'" + socket.id + "'" + ')';
			var result = db.query(queryStr);

			socket.myId = result.insertId;
		}
		//console.log("made a socket connection", CircularJSON.stringify(socket));
	});

	socket.on('disconnect', function(){
		if(socket.myId){
			var	queryStr = 'DELETE FROM sockets WHERE id=' + "'" + socket.myId + "'";
			db.query(queryStr);
		}
	});
});

const updationPage = require('./socket/updationPage/updationPage')(io);
const listingPage = require('./socket/listingPage/Listingpage')(io);
const chat = require('./socket/chat/chat')(io);