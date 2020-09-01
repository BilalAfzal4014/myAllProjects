import axios from "axios";

const api = (type, url, data, headers = {}) => {
    return new Promise((resolve, reject) => {
        axios[type](
            url,
            data,
            headers,
        ).then((response) => {
            if (response.data.meta.code === 465) {
                localStorage.removeItem("logged_in_user_details");
                localStorage.removeItem("fireBase_token");
                localStorage.removeItem("bearer_token");
                window.appObj.removeToken();
            }
            resolve(response);
        }).catch((...error) => {
            reject(error);
        });
    });
};

export default api;
