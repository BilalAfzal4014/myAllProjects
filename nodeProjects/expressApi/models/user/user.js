const db = require('../../db');

module.exports = { 
    getAllUsers: function(){
		queryStr = 'SELECT * FROM users';
		var users = db.query(queryStr);
		return users;
	},
	getUser: function(id){
		queryStr = 'SELECT * FROM users where id=' + "'" + id + "'";
		var user = db.query(queryStr);
		return user[0];
	},
	getLoggedUser: function(id){
		queryStr = 'SELECT email, contactNo FROM users where id=' + "'" + id + "'";
		var user = db.query(queryStr);
		return user[0];	
	},
	updateUser: function(userId, contactNo){
		queryStr = 'Update users set contactNo="' + contactNo + '" where id=' + userId;
		db.query(queryStr);
		return [];
	}
 };