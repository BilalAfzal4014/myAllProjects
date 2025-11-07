const {SocketIdentifiedConnectionHash} = require('../storage');
const {SendMessageHandler, EmitMessageToSocketConnections} = require('../handler');

module.exports = function SendMessage(IO, socket) {
    socket.on('sendMessage', async (data) => {
        try {
            let [requiredSocketsConnection, finalMessage] = await SendMessageHandler(SocketIdentifiedConnectionHash, data);
            EmitMessageToSocketConnections(requiredSocketsConnection, finalMessage, 'receiveMessage', IO);
        } catch (error) {
            console.log('Something went wrong in sendMessage listener of socket', error);
        }
    });
}


