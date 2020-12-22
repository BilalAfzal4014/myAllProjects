export function unRegisterUserActionDispatcher(user) {
    return {
        type: "UNREGISTER_USER",
        payload: user
    };
}

export function pushIntoUnregisterList(user) {
    return {
        type: "UNREGISTER_LIST",
        payload: user
    };
}
