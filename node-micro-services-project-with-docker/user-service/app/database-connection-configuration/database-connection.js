const mysql = require("mysql");

const connection = mysql.createConnection({
    host     : 'mysql-container',
    user     : 'user',
    password : 'pw',
    database : 'mydb'
});

module.exports = connection;