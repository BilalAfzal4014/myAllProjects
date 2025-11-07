const Identity = require('./identity');
const Disconnect = require('./disconnect');
const SendMessage = require('./sendMessage');
const Typing = require('./typing');

function Socket(IO) {
    IO.on('connection', (socket) => {
        console.log('user connected:', socket.id);
        bootStrapAllModules(IO, socket);
    });
}


function bootStrapAllModules(IO, socket) {
    Identity(socket);
    Disconnect(socket);
    SendMessage(IO, socket);
    Typing(IO, socket);
}

module.exports = Socket;