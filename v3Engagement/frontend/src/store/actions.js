export default {
    setStoreTokenAction(context, data) {
        context.commit('setStoreTokenMutations', data);
    },
    removeStoreTokenAction(context) {
        context.commit('removeStoreTokenMutations');
    },
    companyImageUrlAction(context, data) {
        context.commit('companyImageUrlMutations', data);
    },
    saveUserInfoAction(context, data) {
        context.commit("saveUserInfoMutation", data);
    }
};