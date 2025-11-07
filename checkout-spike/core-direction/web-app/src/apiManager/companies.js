import api from "@/apiManager/apiManager";

export const getCategoriesAndCompanies = () => {
    const url = "/company/get-profile-categories-with-companies";
    return api("post", url, {}, "other");
};

export const getFilterCompany = (data) => {
    const url = "/company/get-companies";
    return api("post", url, data, "other");
};

export const updateCompanyFavouriteStatus = (data) => {
    const url = "/user/make-company-favourite";
    return api("post", url, data, "user");
};
