import store from "../../store/store";

const hook = (to, from, next) => {

    if (to.path == "/" && store.getters.getStoreTokenGetters == null) {
        next("/login");
    } else if (store.getters.getStoreTokenGetters == null && to.meta.auth == true) {
        next("/login");
    } else if (store.getters.getStoreTokenGetters != null && to.meta.auth == false) {
        if (store.getters.getUserInfoGetters.is_admin == 0) {
            next("/dashboard/company-userListing");
        } else {
            next("/dashboard/company-listing");
        }
    } else if (store.getters.getStoreTokenGetters != null) {
        if (to.meta.route == "admin" && store.getters.getUserInfoGetters.is_admin == 0) {
            next("/dashboard/company-userListing");
        } else if (to.meta.route == "company" && store.getters.getUserInfoGetters.is_admin == 1) {
            next("/dashboard/company-listing");
        } else {
            next();
        }
    } else {
        next();
    }
};

export default hook;