import axios from 'axios';
import store from "../store/store";
import router from "../router/routes";


const api = function (type, url, data, headers = {}, loader = false) {
    return new Promise(function (resolve, reject) {

        if (loader) {
            $(".ajax_loader").show();
        }
        axios[type](
            url,
            data,
            headers,
        ).then((response) => {
            $(".ajax_loader").hide();
            if (response.data.meta != undefined && (response.data.meta.code == 401 || response.data.meta.code == 465)) {
                store.dispatch('removeStoreTokenAction');
                window.location.href = '/';
            }
            resolve(response);
        }).catch((...error) => {
            if (error[0].response.data.meta != undefined && (error[0].response.data.meta.code == 401 || error[0].response.data.meta.code == 465)) {
                store.dispatch('removeStoreTokenAction');
                window.location.href = '/';
            }
            $(".ajax_loader").hide();
            reject(error);
        });

    });
};

export default api;