const db = require('../../db');

exports = module.exports = function(io){
  io.sockets.on('connection', function (socket) {
    socket.on('updation', function () {
    	
    	queryStr = 'SELECT * FROM sockets where auth_id!=' + socket.myAuthId;
      var dbSocket = db.query(queryStr);

      if(dbSocket.length > 0){
        var sockobj = io.sockets.connected[dbSocket[dbSocket.length - 1].socketObj];
        sockobj.emit('updation', socket.myUserId + ' is on updation page');
      }
      
    });
  
  });

}



// information about sockets

  // io.sockets.emit  all the sockets including myself
  // socket.broadcast.emit all the sockets except myself
  // socket.emit current socket only


// queryStr = 'SELECT * FROM sockets where id='51;
   // var socket = db.query(queryStr);

/*
	socket.emit('abc', obj, function(data){					// on client side
		// this will trigger when call back called	
	});
	
	socket.on('abc', function(data, callback){	// on server side
		callback(passanything)
	});

get socket by id, will also give custom added attributes
io.sockets.connected[socketId] 


// socket by id, will not give custom attribute
io.to(socketid).emit();


*/