const {SocketIdentifiedConnectionHash} = require('../storage');
const {RemoveConnection} = require('../handler');

module.exports = function Disconnect(socket) {
    socket.on('disconnect', () => {
        try {
            console.log('user disconnected:', socket.id, socket.identity);
            RemoveConnection(SocketIdentifiedConnectionHash, socket);
        } catch (error) {
            console.log('Something went wrong in disconnect listener of socket', error);
        }
    });
}


