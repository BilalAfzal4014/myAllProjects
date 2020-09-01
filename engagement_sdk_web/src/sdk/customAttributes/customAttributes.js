import api from "../apiCall/apiCall";
import constants from "../constants/constants";

let customAttributes = {
    saveCustomAttributes(userData, actionData, conversionData) {
        let extraParams = localStorage.getItem("extra_params");
        extraParams = extraParams != null ? JSON.parse(extraParams) : {
            user_data: {},
            action_data: [],
            conversion_data: []
        };

        extraParams.user_data = {
            ...extraParams.user_data,
            ...userData
        };

        extraParams.action_data = [
            ...extraParams.action_data,
            ...actionData
        ];

        extraParams.conversion_data = [
            ...extraParams.conversion_data,
            ...conversionData
        ];

        uniqueArr(extraParams.action_data);
        uniqueArr(extraParams.conversion_data);

        localStorage.setItem("extra_params", JSON.stringify(extraParams));
    },
    postCustomAttributes(callback) {

        let extraParams = localStorage.getItem("extra_params");
        extraParams = extraParams != null ? JSON.parse(extraParams) : {
            user_data: {},
            action_data: [],
            conversion_data: []
        };


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
                mode: "update",
                user_id: user.user_id,
                device_token: user.device_token,
                extra_params: extraParams
            }
        };

        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                localStorage.removeItem("extra_params");
                callback(true);
            } else {
                callback(false);
            }
        }).catch((er) => {

        });

    },
    postUserAttributesOnly(callback) {
        let extraParams = localStorage.getItem("extra_params");
        extraParams = extraParams != null ? JSON.parse(extraParams) : {
            user_data: {},
            action_data: [],
            conversion_data: []
        };

        let originalParams = {
            ...extraParams
        };

        extraParams.action_data = [];
        extraParams.conversion_data = [];

        originalParams.user_data = {};

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
                mode: "update",
                user_id: user.user_id,
                device_token: user.device_token,
                extra_params: extraParams
            }
        };

        api("post",
            constants.getUrl("base"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                localStorage.setItem("extra_params", JSON.stringify(originalParams));
                callback(true);
            } else {
                callback(false);
            }
        }).catch((er) => {

        });
    }
};

function uniqueArr(array) {
    for (let i = 0; i < array.length; i++) {
        for (let j = i + 1; j < array.length; j++) {
            if (array[i].code == array[j].code && array[i].value == array[j].value) {
                array.splice(j, 1);
                j--;
            }
        }
    }
}

export default customAttributes;

