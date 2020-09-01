import api from "../apiCall/apiCall";
import constants from "../constants/constants";

let conversionTrigger = {
    getConversionListing(callback) {
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
            resource: "get/action",
            method: "list",
            data: {
                data_type: "conversion",
                user_id: user.user_id
            }
        };


        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                callback(res.data.data.conversion);
            } else {
                callback(null);
            }
        }).catch((er) => {

        });
    },
    getUserPerformedConversion(callback) {
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
            resource: "get/user/action",
            method: "list",
            data: {
                user_id: user.user_id,
                data_type: "action_trigger"
            }
        };


        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                callback(res.data.data.conversion_trigger);
            } else {
                callback(null);
            }
        }).catch((er) => {

        });
    },
    reqConversionActions(item, callback) {

        let trackKeys = localStorage.getItem("track_keys");

        if (trackKeys == null) {
            alert("Perform some action triggers campaign first");
            return;
        }


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
            resource: "campaign/conversion/trigger",
            method: "send",
            data: {
                ...item,
                user_id: user.user_id,
                track_key: "asdfasdfasdf"
            }
        };

        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                callback(res.data.data.action);
            } else {
                callback(null);
            }
        }).catch((er) => {

        });


    }
};

export default conversionTrigger;