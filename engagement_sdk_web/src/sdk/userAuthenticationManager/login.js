import api from "../apiCall/apiCall";
import constants from "../constants/constants";

let userLogin = {
    loginViaCompanyKey(data, callback) {

        const payLoad = {
            company_key: data.company_key
        };

        const reqHeaders = {
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            }
        };

        api("post",
            constants.getUrl("login"),
            payLoad,
            reqHeaders
        ).then((res) => {
            if (res.data.meta.status === "success") {
                localStorage.setItem("bearer_token", "Bearer " + res.data.data.token);
                callback(true);
            } else {
                callback(false);
            }
        }).catch((er) => {

        });
    },
    InitializePlatform(userObj, token, callback) {
        if (token == null) {
            token = localStorage.getItem("fireBase_token");
        }

        let user = {
            ...userObj
        };

        user.device_token = token;
        user['instance-id'] = token.split(":")[0];
        user.username = user.firstname + user.lastname;

        let remainingHeaders = {
            'app-id': user.app_id,
            'app-name': user.app_name,
            'lang': user.language,
            'device-token': user.device_token
        };

        this.getUserLocation(user, function () {
            delete user.app_id;
            delete user.app_name;
            delete user.company_key;
            delete user.language;

            const reqHeaders = {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': localStorage.getItem("bearer_token"),
                    'device-type': "web",
                    'app-version': 1.2,
                    'app-build': 1,
                    ...remainingHeaders
                }
            };

            const payLoad = {
                resource: "user",
                method: "subscribe",
                data: {
                    ...user
                }
            };

            api("post",
                constants.getUrl("base"),
                payLoad,
                reqHeaders
            ).then((res) => {
                if (res.data.meta.status === "success") {
                    localStorage.setItem("logged_in_user_details", JSON.stringify(res.data.data));
                    callback(res.data.data);
                } else {
                    callback(null);
                }
            }).catch((er) => {

            });
        });
    },
    getUserLocation(user, callback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                user.latitude = position.coords.latitude;
                user.longitude = position.coords.longitude;
                callback();
            });
        } else {
            callback();
        }
    }
};

export default userLogin;