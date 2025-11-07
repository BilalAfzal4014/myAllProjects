import api from "@/apiManager/apiManager";

export const getUserBookedActivities = () => {
    const url = "/booking/get-user-calendar-booking";
    return api("get", url, null, "other");
};
