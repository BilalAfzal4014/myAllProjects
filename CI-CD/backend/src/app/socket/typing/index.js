const {SocketIdentifiedConnectionHash} = require('../storage');
const {TypingHandler, EmitMessageToSocketConnections} = require('../handler');

module.exports = function Typing(IO, socket) {
    socket.on('typing', async (data) => {
        try {
            let [requiredSocketsConnection, typer] = await TypingHandler(SocketIdentifiedConnectionHash, data, 'typing');
            EmitMessageToSocketConnections(requiredSocketsConnection, typer, 'typer', IO);
        } catch (error) {
            console.log('Something went wrong in typing listener of socket', error);
        }
    });

    socket.on('stoppedTyping', async (data) => {
        try {
            let [requiredSocketsConnection, typer] = await TypingHandler(SocketIdentifiedConnectionHash, data, 'stoppedTyping');
            EmitMessageToSocketConnections(requiredSocketsConnection, typer, 'typer', IO);
        } catch (error) {
            console.log('Something went wrong in stoppedTyping listener of socket', error);
        }

    });
}


