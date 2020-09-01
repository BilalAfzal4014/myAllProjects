const express = require('express');
const router = express.Router();
const userModel = require('../../models/user/user');
const authMiddleware = require('../../authMiddleware/apiAuth');

router.get('/', function (req, res, next) {

	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);

	res.status(200).json({
			status: true,
			data: userModel.getAllUsers(),
			message: "All User Listing"
	});
});

router.post('/', function (req, res, next) {

	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);

	res.status(200).json({ 
			status: true,
			data: userModel.getLoggedUser(req.body.userId),	
			message: "Logged User Details"
	});
	
});

router.post('/update', function (req, res, next) {

	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);

	res.status(200).json({ 
			status: true,
			data: userModel.updateUser(req.body.userId, req.body.contactNo),	
			message: "User Details Updated"
	});
	
});

router.get('/:userId', function (req, res, next) {

	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);

	res.status(200).json({
			status: true,
			data: userModel.getUser(req.params.userId),
			message: "User Found"
	});
});


module.exports = router;