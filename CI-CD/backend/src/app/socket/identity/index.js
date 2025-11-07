const {SocketIdentifiedConnectionHash} = require('../storage');
const {StoreConnection} = require('../handler');

module.exports = function Identity(socket) {
    socket.on('identified', (data) => {
        try {
            StoreConnection(SocketIdentifiedConnectionHash, data, socket);
        } catch (error) {
            console.log('Something went wrong in identified listener of socket', error);
        }
    });
}



