const userLoginToken = (state = null, action) => {
    state = localStorage.getItem("user_token");

    switch (action.type) {
        case "SAVE_TOKEN":
            state = JSON.stringify(action.payLoad);
            localStorage.setItem("user_token", state);
            break;
        case "MODIFY_TOKEN_NOTIFICATION":
            let userObj = JSON.parse(localStorage.getItem("user_token"));
            userObj.email_notification = action.payLoad.email_notification;
            userObj.enable_notification = action.payLoad.enable_notification;
            state = JSON.stringify(userObj);
            localStorage.setItem("user_token", state);
            break;
        case "REMOVE_TOKEN":
            state = null;
            localStorage.removeItem("user_token");
            break;
    }

    return state;
};

export default userLoginToken;