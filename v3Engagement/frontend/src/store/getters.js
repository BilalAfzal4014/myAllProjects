export default {
    getStoreTokenGetters(state) {
        var token = localStorage.getItem('token');
        return token;
    },
    getCompanyImageUrl(state) {
        return state.companyImageUrl;
    },
    getUserInfoGetters(state) {
        return JSON.parse(localStorage.getItem('userInfo'));
    }
};