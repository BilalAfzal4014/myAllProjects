export function saveTokenAction(data) {
    return {
        type: "SAVE_TOKEN",
        payLoad: data
    };
}

export function removeTokenAction() {
    return {
        type: "REMOVE_TOKEN",
        payLoad: {}
    };
}

export function modifyTokenNotificationAction(data) {
    return {
        type: "MODIFY_TOKEN_NOTIFICATION",
        payLoad: data
    };
}