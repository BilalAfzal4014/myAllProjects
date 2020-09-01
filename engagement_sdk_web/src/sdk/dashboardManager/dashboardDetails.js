import constants from "../constants/constants";

let dashboardDetails = {
    getDashboardDetails(callback) {
        let user = JSON.parse(localStorage.getItem("logged_in_user_details"));
        callback({
            baseUrl: constants.getUrl("base"),
            app_name: user.app_name,
            user_id: user.user_id,
            email: user.email,
            isNotificationOn: user.email_notification == 1 && user.enable_notification == 1 ? true : false,
        });
    }
};

export default dashboardDetails;