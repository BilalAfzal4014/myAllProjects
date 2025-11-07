const axios = require("axios");

const communicateViaAxios = ({
 method,
 url,
 data,
 headers,
 auth,
 responseType
}) => {
    return axios({
        method,
        url,
        headers,
        data,
        auth,
        responseType,
    });
};

module.exports = {communicateViaAxios}