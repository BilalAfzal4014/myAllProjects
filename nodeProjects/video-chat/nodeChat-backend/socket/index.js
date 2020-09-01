let onlineAgents = [];
// do not open two tabs in same browser bcz i am handling uniqueness on browser agent basis for this spike, so i can concentrate more on spike functionality

let mapping = {
    chromeincognito: null,
    chrome: null,
    firefox: null,
};

function handleSocketLogic(io) {

    io.sockets.on("connection", function (socket) {

        socket.on("makeActiveAgent", function (data) {
            socket.myId = data;
            mapping[data] = socket.id
            onlineAgents.push(data);
            for (let agent of onlineAgents) {
                let socketObj = io.sockets.connected[mapping[agent]];
                if (socketObj) {
                    socketObj.emit("getActiveAgent", onlineAgents);
                }
            }
            //onlineAgents.push(data);
        });

        socket.on("disconnect", function () {
            if (socket.myId) {
                const index = onlineAgents.indexOf(socket.myId);
                onlineAgents.splice(index, 1);
                mapping[socket.myId] = null;
                for (let agent of onlineAgents) {
                    let socketObj = io.sockets.connected[mapping[agent]];
                    if (socketObj) {
                        socketObj.emit("removeActiveAgent", socket.myId);
                    }
                }
            }

        });

        socket.on("signal", function (data) {
            let socketObj = io.sockets.connected[mapping[data.to]];
            if (socketObj) {
                socketObj.emit(data.peer.type, data);
            }
        });

    });
}

module.exports = handleSocketLogic;