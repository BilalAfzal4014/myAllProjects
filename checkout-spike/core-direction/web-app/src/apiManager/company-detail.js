import api from "@/apiManager/apiManager";
import { transformObjectIntoQueryString } from "@/common/constants/constants";

export const getCompanyDetail = (data) => {
  const url = `/user/v2/get-company-detail?${transformObjectIntoQueryString(
    data
  )}`;
  return api("get", url, null, "user");
};
