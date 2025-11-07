import api from "@/apiManager/apiManager";

export const getNotificationList = (data) => {
    const url = "/get-notification-list";
    return api("post", url, data, "notification");
};

export const updateNotificationStatus  = (data) => {
    const url = "/update-notification";
    return api("patch", url, data, "notification");
};
export const updateNotificationMessage  = (data) => {
    const url = "/update-notification-message";
    return api("patch", url, data, "notification");
};
