const db = require('../db');

module.exports = { 

	apiAuthentication: function(res, Authorization, userId, id){
		queryStr = 'SELECT * FROM authtokens WHERE id=' + '"' + id + '"' + ' AND ' + 'user_id=' + '"' + userId + '"' + ' AND ' + 'token=' + '"' + Authorization + '"';
		var verify = db.query(queryStr);
		if(verify.length > 0){
			return true;
		}
		res.status(404).json({
			status: false,
			data: [],
			message: "unauthenticated"
	});
	}

 };