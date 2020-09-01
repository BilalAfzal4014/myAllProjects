const db = require('../../db');

exports = module.exports = function(io){
  io.sockets.on('connection', function (socket) {
    socket.on('chat', function (data) {
      queryStr = "INSERT INTO chat_messages (chat_id, sender_id, message) VALUES (" +  data.chat_id +  "," + data.sender_id  + "," +  "'" + data.message + "'" + ")";
      db.query(queryStr);

      queryStr = "SELECT sockets.socketObj FROM chat_members JOIN sockets on chat_members.member_id = sockets.user_id WHERE chat_members.chat_id = " + data.chat_id;
      var memberSocketsId = db.query(queryStr);

      for(var i = 0; i < memberSocketsId.length; i++){
        var sockobj = io.sockets.connected[memberSocketsId[i].socketObj];
        sockobj.emit('chat', data);
      }
    
    });

    socket.on('typing', function (data) {
        queryStr = "SELECT sockets.socketObj FROM chat_members JOIN sockets on chat_members.member_id = sockets.user_id WHERE chat_members.chat_id = " + data.chat_id + " AND chat_members.member_id != " + data.sender_id;
        var memberSocketsId = db.query(queryStr);
        
        for(var i = 0; i < memberSocketsId.length; i++){
          var sockobj = io.sockets.connected[memberSocketsId[i].socketObj];
          sockobj.emit('typing', {
            chat_id: data.chat_id,
            typing_id: data.sender_id,
            type: data.type
          });
        }
      
    });

  });
};

    