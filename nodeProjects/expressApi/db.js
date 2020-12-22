//const mySQLAsy = require('mysql');

const mySqlSy = require('sync-mysql');

// for asynch

// var connection1 = mySQLAsy.createConnectionSync({
//   host: 'localhost',
//   user: 'root',
//   password: '',
//   database: 'formsub'
// })

//connection.connect(function(err) {
  //if (err) throw err
	//console.log(connection);
//})


// for synch

var connection = new mySqlSy({
 	host: 'localhost',
  	user: 'root',
  	password: 'eureka123',
  	database: 'formsub'
});

module.exports = connection;