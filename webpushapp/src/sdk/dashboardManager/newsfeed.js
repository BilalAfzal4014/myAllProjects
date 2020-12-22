import api from "../apiCall/apiCall";
import constants from "../constants/constants";

let newsfeed = {
    getNewsFeedListing(callback) {
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
            resource: "get/news_feed",
            method: "listing",
            data: {
                user_id: user.user_id,
                latitude: user.latitude,
                longitude: user.longitude
            }
        };


        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                callback(res.data.data.content);
            } else {
                callback(null);
            }
        }).catch((er) => {

        });
    },
    getNewsFeedCount(callback) {
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
            resource: "get/news_feed",
            method: "listing",
            data: {
                user_id: user.user_id,
                latitude: user.latitude,
                longitude: user.longitude
            }
        };


        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                callback({
                    total_newsfeed: res.data.data.total_newsfeed,
                    unread_newsfeed: res.data.data.unread_newsfeed
                });
            } else {
                callback(null);
            }
        }).catch((er) => {

        });
    }
};

export default newsfeed;