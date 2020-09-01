export default {
    setStoreTokenMutations(state, data) {
        localStorage.setItem('token', "Bearer " + data);
    },
    removeStoreTokenMutations(state) {
        localStorage.removeItem('token');
        localStorage.removeItem('userInfo');
    },
    companyImageUrlMutations(state, data) {
        state.companyImageUrl = data;
    },
    saveUserInfoMutation(state, data) {
        localStorage.setItem('userInfo', JSON.stringify(data));
    }
};