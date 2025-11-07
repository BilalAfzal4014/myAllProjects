const axios = require('axios');

function api(type, url, data = null, configurations = {}) {
    return axios[type](url, data, configurations)
        .then(response => response.data)
        .catch((error) => {
            console.log('something went wrong with api call', type, url, data);
            throw error;
        });
}

module.exports = api;
