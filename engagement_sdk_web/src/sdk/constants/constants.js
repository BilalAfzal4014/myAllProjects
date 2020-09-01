const constants = {
    baseURL: "http://authhrmv3.entertainerebizservices.com/api/v1/",
    login: "login",
    base: "",
    getUrl(key) {
        return this.baseURL + this[key];
    }
};

export default constants;