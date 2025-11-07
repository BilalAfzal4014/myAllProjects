require('dotenv').config();
const http = require('http');
const {Server} = require('socket.io');
const app = require('./app/index');
const Socket = require('./app/socket');
const {establishDatabaseConnection} = require('./app/databases/sql-connection');


function startServer() {
    const port = process.env.PORT;
    const server = http.createServer(app);
    server.listen(port, () => {
        Socket(new Server(server, {
            cors: {
                origin: process.env.FRONTEND_APP_URL,
                //methods: ['GET', 'POST']
            }
        }));
        console.log(`Server is listening on port ${port}`);
    });
}

establishDatabaseConnection()
    .then(() => {
        startServer();
    }).catch((error) => {
    console.error('Could not start Application due to', error);
    process.exit(-1);
});

