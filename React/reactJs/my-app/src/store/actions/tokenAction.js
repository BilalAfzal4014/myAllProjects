export function setTokenAction(token) {
    return {
        type: "SAVE_TOKEN",
        payload: token
    };
}

export function removeTokenAction() {
    return {
        type: "REMOVE_TOKEN",
        payload: {}
    };
}
