import axios from "axios";

const oldApi = function (type, url, data = {}, loader = false) {
    const headers = {
        headers: {
            auth: {
                username: this.constants.basicAuth.username,
                password: this.constants.basicAuth.password,
            },
            headers: {},
        },
    };
    if (localStorage.getItem("token")) {
        headers.headers.headers["auth"] = localStorage.getItem("token");
    }
    return new Promise(function (resolve, reject) {
        if (loader) showLoader();
        if (type === "get" || type === "delete") {
            axios[type](url, headers.headers, data)
                .then((response) => {
                    if (loader) hideLoader();
                    resolve(response);
                })
                .catch((error) => {
                    errorHandler(reject, error);
                });
        } else {
            if (loader) showLoader();
            axios[type](url, data, headers.headers)
                .then((response) => {
                    if (loader) hideLoader();
                    resolve(response);
                })
                .catch((error) => {
                    errorHandler(reject, error);
                });
        }
    });
};
const errorHandler = (reject, error) => {
    if (error.response.status === 401 || error.response.status === 403)
        removeDataFromLocalStorage();
    $("body").removeClass("overflow-y-hidden");
    $(".ajax_loader").hide();
    reject(error.response.data);
};
const removeDataFromLocalStorage = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("userProfile");
    window.location.reload();
};
const hideLoader = () => {
    $("body").removeClass("overflow-y-hidden");
    $(".ajax_loader").hide();
};
const showLoader = () => {
    $("body").addClass("overflow-y-hidden");
    $(".ajax_loader").show();
};
export default oldApi;
