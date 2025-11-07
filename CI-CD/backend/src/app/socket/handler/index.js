const SendMessageUseCase = require('../../usecases/message/send');
const FetchUserUseCase = require('../../usecases/user/fetch');
const FetchUserChatGroupMemberUseCase = require('../../usecases/userChatGroupMember/fetch');


function StoreConnection(SocketIdentifiedConnectionHash, data, socket) {
    if (!SocketIdentifiedConnectionHash[data.identity]) {
        SocketIdentifiedConnectionHash[data.identity] = [];
    }
    socket.identity = data.identity;
    SocketIdentifiedConnectionHash[data.identity].push(socket.id);
}


function RemoveConnection(SocketIdentifiedConnectionHash, socket) {
    if (!!socket.identity) {
        const index = SocketIdentifiedConnectionHash[socket.identity].indexOf(socket.id);
        SocketIdentifiedConnectionHash[socket.identity].splice(index, 1);
        if (!SocketIdentifiedConnectionHash[socket.identity].length) delete SocketIdentifiedConnectionHash[socket.identity];
    }
}

async function SendMessageHandler(SocketIdentifiedConnectionHash, data) {
    const messageContext = await (new SendMessageUseCase(data)).send();
    const senderInformation = await FetchUserUseCase.fetchById(data.userId);
    const finalMessage = {
        message: {
            message_id: messageContext.message.id,
            text: messageContext.message.text,
            sender_id: data.userId,
            name: senderInformation.name
        },
        groupId: data.groupId,
        ...(data.userIds && {userIds: data.userIds}),
        senderId: data.userId
    };

    let requiredSocketsConnection = [];
    messageContext.messageDetails.forEach((messageDetail) => {
        requiredSocketsConnection = [...requiredSocketsConnection, ...SocketIdentifiedConnectionHash[messageDetail.userId]?.map((socketId) => socketId) ?? []];
    });
    return [requiredSocketsConnection, finalMessage];
}

async function TypingHandler(SocketIdentifiedConnectionHash, data, event) {
    let [typerInformation, members] = await Promise.all([
        event === 'typing' ? FetchUserUseCase.fetchById(data.userId) : Promise.resolve({name: ''}),
        !!data.groupId ? FetchUserChatGroupMemberUseCase.fetchUserChatGroupId(data.groupId) : FetchUserUseCase.fetchById(data.individualId)
    ]);
    const typer = {
        name: typerInformation.name,
        groupId: data.groupId,
        individualId: data.individualId,
        typerId: data.userId
    };
    members = !!data.individualId ? [members] : members;
    let requiredSocketsConnection = [];
    members.forEach((member) => {
        const idToCompare = !!data.groupId ? member.userId : member.id;
        if (idToCompare !== data.userId) {
            requiredSocketsConnection = [...requiredSocketsConnection, ...SocketIdentifiedConnectionHash[idToCompare]?.map((socketId) => socketId) ?? []];
        }
    });
    return [requiredSocketsConnection, typer];
}

function EmitMessageToSocketConnections(socketConnections, message, event, IO) {
    socketConnections.forEach((socketId) => {
        const socket = IO.sockets.sockets.get(socketId);
        socket.emit(event, message);
    });
}


module.exports = {
    StoreConnection,
    RemoveConnection,
    SendMessageHandler,
    TypingHandler,
    EmitMessageToSocketConnections
};