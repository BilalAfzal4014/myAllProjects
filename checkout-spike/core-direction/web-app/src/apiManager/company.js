import api from "@/apiManager/apiManager";

export const getAllCategories = (data) => {
    const url = "/filter/profile_category";
    return api("get", url, null, "other");
};
