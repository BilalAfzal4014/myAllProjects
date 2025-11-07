export default {
    getStoreTokenGetters(state) {
        state.token = localStorage.getItem("token");
        return state.token;
    },
    getStoreUserProfileGetters: (state) => () => {
        const userProfile = localStorage.getItem("userProfile");
        return (state.userProfile = JSON.parse(userProfile));
    },
    getStoreHeaderSearchGetters(state) {
        return state.headerSearch;
    },
    getCompaniesList(state) {
        return state.companies;
    },
    getFilteredCompanies(state) {
        return state.filteredCompanies;
    },
    getWearableModalValue(state) {
        return state.showWearableModal;
    }

};
