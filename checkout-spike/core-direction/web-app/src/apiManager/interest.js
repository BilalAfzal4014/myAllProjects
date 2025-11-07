import api from "@/apiManager/apiManager";

export const getInterestList = () => {
    const url = "/interests";
    return api("get", url, null, "common");
};
