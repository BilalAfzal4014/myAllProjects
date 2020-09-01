const db = require('../../db');

module.exports = { 
	makeChatRoom: function(ids){
		 queryStr = "SELECT chat_id, COUNT(*) as total FROM chat_members WHERE member_id IN (" + ids + ") GROUP By chat_id HAVING COUNT(chat_id) = " + ids.length;
		 var chatObj = db.query(queryStr);


		 for (var i = 0; i < chatObj.length ; i++) {
		 	queryStr = 'SELECT COUNT(chat_id) as total FROM `chat_members` WHERE chat_id=' + chatObj[i].chat_id;
		 	var memberCount = db.query(queryStr);
		 	 if(memberCount[0].total == chatObj[i].total){
				return false;
		 	}
		 }		 
		
		 queryStr = "INSERT INTO chat_room (name) VALUES ('chatroomCreated')";
		 var chat = db.query(queryStr);


		 for (var i = 0; i < ids.length ; i++) {
		 	queryStr = "INSERT INTO chat_members (chat_id, member_id) VALUES (" +  chat.insertId +  ',' +  ids[i] + ")";
		 	db.query(queryStr);
		 }

		 return true;	
		  
	},
	getChatListing: function(userid){
		queryStr = "SELECT DISTINCT chat_id FROM chat_members WHERE member_id = " + userid;
		var chatIds = db.query(queryStr);

		var chatListing = [];

		for (var i = 0; i < chatIds.length ; i++) {
			queryStr = "SELECT COUNT(*) as totalMembers FROM chat_members WHERE member_id != " + userid + " AND " + "chat_id = " + chatIds[i].chat_id;
		 	var memberCount = db.query(queryStr);

		 	if(memberCount[0].totalMembers == 1){
		 		queryStr = "SELECT chat_members.chat_id as id, users.email as name  FROM chat_members JOIN users on chat_members.member_id = users.id WHERE chat_members.member_id != " + userid + " AND " + "chat_id = " + chatIds[i].chat_id;
		 		var chatObj = db.query(queryStr);
		 	}
		 	else{
		 		queryStr = "SELECT id, name  FROM chat_room WHERE chat_room.id = " + chatIds[i].chat_id;
		 		var chatObj = db.query(queryStr);
		 	}

		 	if(i == 0)
		 		chatObj[0].active = 1;
		 	else
		 		chatObj[0].active = 0;

		 	chatListing.push(chatObj[0]);
		 }	
		 return chatListing;
	},
	getMessages: function(chatId){
		queryStr = "SELECT * FROM chat_messages WHERE chat_id = " + chatId;
		var messages = db.query(queryStr);
		return messages;
	}
	
 };