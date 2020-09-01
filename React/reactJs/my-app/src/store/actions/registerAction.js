export function registerUserActionDispatcher(user) {
    return {
        type: "REGISTER_USER",
        payload: user
    };
}

export function pushIntoRegisterList(user) {
    return {
        type: "REGISTER_LIST",
        payload: user
    };
}

// below is the code of if we want to dispatch action after network call
// time out will be network call, associated with thunk in middleware
// will do network call in promise, associated with promise in middleware


/*export function setName(name) {
    return dispatch => {
        setTimeout(() => {
            dispatch({
                type: "SET_NAME",
                payload: name
            });
        }, 2000);
    }

    return {
        type: "SET_NAME",
        payload: new Promise((resolve, reject) => {
            setTimeout(() => {
                resolve(name);
            }, 2000);
        })
    };
}*/

