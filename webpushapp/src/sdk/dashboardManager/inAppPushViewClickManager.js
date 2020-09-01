import api from "../apiCall/apiCall";
import constants from "../constants/constants";

let viewClickManager = {
    viewClick(notificationObj, mode, callback) {
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
            resource: "campaign/push/tracking",
            method: "service",
            data: {
                user_id: user.user_id,
                track_key: notificationObj.data.track_key,
                mode,
                action_url: notificationObj.data.params['Deep Link']
            }
        };

        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            user.email_notification = res.data.data.email_notification;
            user.enable_notification = res.data.data.enable_notification;
            localStorage.setItem("logged_in_user_details", JSON.stringify(user));
            if (res.data.meta.status === "success") {
                callback({
                    email_notification: user.email_notification,
                    enable_notification: user.enable_notification
                });
            } else {
                callback(null);
            }
        }).catch((er) => {

        });
    }
};

export default viewClickManager;