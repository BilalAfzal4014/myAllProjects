const db = require('../../db');

exports = module.exports = function(io){
  io.sockets.on('connection', function (socket) {
    socket.on('listing', function () {
      queryStr = 'SELECT * FROM sockets where auth_id!=' + socket.myAuthId;
      var dbSocket = db.query(queryStr);

      if(dbSocket.length > 0){
      	var sockobj = io.sockets.connected[dbSocket[dbSocket.length - 1].socketObj];
      	sockobj.emit('listing', socket.myUserId + ' is on listing page');
  	  }

    });
  });
}