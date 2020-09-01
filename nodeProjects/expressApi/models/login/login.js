const db = require('../../db');
const bcrypt = require('bcrypt');

module.exports = { 

	authentication: function(email, password){

		 queryStr = 'SELECT * FROM users WHERE email=' + '"' + email + '"';
		 var user = db.query(queryStr);
		 if(user.length > 0){
		 	return bcrypt.compareSync(password, user[0].password);
		 }
		 return false;
	},
	generateToken: function(email){
		queryStr = 'SELECT * FROM users WHERE email=' + '"' + email + '"';
		var user = db.query(queryStr);
		var token = bcrypt.hashSync(email, 10);
		var queryStr = "INSERT INTO authtokens (user_id, token) VALUES (" + user[0].id + "," + "'" + token + "'" + ")";
		var result = db.query(queryStr);

		return {
			id: result.insertId,
			userId: user[0].id,	
			Authorization: token
		};
	},
	logout: function(id, userid){

		var	queryStr = 'DELETE FROM sockets WHERE auth_id=' + "'" + id + "'";
		db.query(queryStr);
		queryStr = 'DELETE FROM authtokens WHERE id=' + '"' + id + '" AND user_id=' + '"' + userid + '"';
		db.query(queryStr);
		return [];
	}

 };