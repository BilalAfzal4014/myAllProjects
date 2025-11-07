import api from "@/apiManager/apiManager";
import { transformObjectIntoQueryString } from "@/common/constants/constants";

export const getVideoCategories = () => {
    const url = "/media-on-demand/get-all-categories";
    return api("get", url, null, "video");
};

export const getVideoSubCategories = (categoryId) => {
    const url = `/media-on-demand/get-sub-categories{categoryId}?categoryId=${categoryId}`;
    return api("get", url, null, "video");
};

export const getAdvanceFilterList = () => {
    const url = "/media-on-demand/get-advance-filters";
    return api("get", url, null, "video");
};

export const getVideoContent = (data) => {
    const url = `/media-on-demand/get-content-list?${transformObjectIntoQueryString(
        data
    )}`;
    return api("get", url, null, "video");
};

export const getFavoriteVideoContent = (data) => {
    const url = `/media-on-demand/get-fvt-content-list?${transformObjectIntoQueryString(
        data
    )}`;
    return api("get", url, null, "video");
};

export const updateVideoStatus = (data) => {
    const url = "/media-on-demand/make-content-fvt";
    return api("post", url, data, "video");
};

export const getVideoContentDetail = (id) => {
    const url = "/media-on-demand/get-content/" + id;
    return api("get", url, null, "video");
};
