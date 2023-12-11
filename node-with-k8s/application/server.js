const http = require('http');
const mysql = require('mysql2');

const connection = mysql.createConnection({
    host: process.env.db_host,
    user: process.env.db_user,
    password: process.env.db_password,
    database: process.env.db_name,
    port: +process.env.db_port,
});

connection.connect();
http.createServer((req, res) => {
    connection.query('SELECT * from user', function (error, results, _) {
        if (error) throw error;
        res.write(JSON.stringify(results));
        res.end();
    });
}).listen(3000, () => {
    console.log('server is listening on port', 3000);
});

