const express = require('express');
const router = express.Router();
const chatModel = require('../../models/chat/chat');
const authMiddleware =  require('../../authMiddleware/apiAuth');

router.post('/makechatroom', function (req, res, next) {
	
	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);
	if(chatModel.makeChatRoom(req.body.userIdsForChatRoom)){
		res.status(200).json({
			status: true,
			data:[],
			message: "Chat room created"
		});
	}
	
	res.status(200).json({
		status: false,
		data:[],
		message: "Chat room already exist"
	});
	
});

router.get('/getchatlisting/:userid', function (req, res, next) {
	
	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);
	res.status(200).json({
		status: true,
		data: chatModel.getChatListing(req.params.userid),
		message: "Chat Listing"
	});
	
});

router.get('/getmessaging/:chatId', function (req, res, next) {
	
	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);
	res.status(200).json({
		status: true,
		data: chatModel.getMessages(req.params.chatId),
		message: "Messages"
	});
	
}); 





module.exports = router;