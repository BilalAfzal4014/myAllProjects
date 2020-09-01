const express = require('express');
const router = express.Router();
const loginModel = require('../../models/login/login');
const authMiddleware =  require('../../authMiddleware/apiAuth');

router.post('/login', function (req, res, next) {

	if(loginModel.authentication(req.body.email, req.body.password)){
		res.status(200).json({
			status: true,
			data: loginModel.generateToken(req.body.email),
			message: 'authenticated',
		});
	}
	res.status(200).json({
		status: false,
		data: [],
		message: 'Email or password is incorrect',
	})
}); 

router.get('/logout', function (req, res, next) {

	authMiddleware.apiAuthentication(res, req.headers.authorization, req.headers.userid, req.headers.id);
	res.status(200).json({
			status: true,
			data: loginModel.logout(req.headers.id, req.headers.userid),
			message: "User Logout"
	});
});

module.exports = router;