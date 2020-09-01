import api from "../apiCall/apiCall";
import constants from "../constants/constants";

let userLogout = {
    logout(callback) {
        let user = JSON.parse(localStorage.getItem("logged_in_user_details"));

        let remainingHeaders = {
            'app-id': user.app_id,
            'app-name': user.app_name,
            'lang': user.lang,
            'device-token': user.device_token
        };

        const reqHeaders = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': localStorage.getItem("bearer_token"),
                'device-type': "web",
                'app-version': 1.2,
                'app-build': 1,
                'timezone': Intl.DateTimeFormat().resolvedOptions().timeZone,
                ...remainingHeaders
            }
        };

        const payLoad = {
            resource: "user",
            method: "subscribe",
            data: {
                mode: "logout",
                user_id: user.user_id,
                user_token: user.user_token
            }
        };

        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                localStorage.removeItem("logged_in_user_details");
                localStorage.removeItem("fireBase_token");
                localStorage.removeItem("bearer_token");
                callback(true);
            } else {
                callback(false);
            }
        }).catch((er) => {

        });
    },
};

export default userLogout;