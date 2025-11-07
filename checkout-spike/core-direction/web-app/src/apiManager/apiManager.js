import axios from "axios";
import router from "@/router";
import * as toastr from "toastr";
import { refreshAccessToken } from "@/apiManager/user";

const axiosApiInstance = axios.create();

const getBaseUrl = (baseUrl) => {
  return {
    auth: process.env.VUE_APP_AUTH_SERVICE,
    user: process.env.VUE_APP_USER_SERVICE,
    common: process.env.VUE_APP_COMMON_SERVICE,
    media: process.env.VUE_APP_MEDIA_SERVICE,
    activity: process.env.VUE_APP_ACTIVITY_DIARY_BASE_URL,
    other: process.env.VUE_APP_BASE_URL,
    group: process.env.VUE_APP_GROUPS_BASE_URL,
    notification: process.env.VUE_APP_NOTIFICATION_BASE_URL,
    video: process.env.VUE_APP_VIDEO_ON_DEMAND_BASE_URL,
    gamification: process.env.VUE_APP_GAMIFICATION_SERVICE_BASE_URL,
    offers: process.env.VUE_APP_DEALS_AND_OFFERS,
    premium: process.env.VUE_APP_CORE_PREMIUM,
  }[baseUrl];
};

const getToken = () => {
  return localStorage.getItem("token");
};

const getAuthHeader = (service) => {
  const token = getToken();
  if (service === "other") {
    return token
      ? {
        auth: token,
      }
      : {};
  } else {
    return token
      ? {
        Authorization: token,
      }
      : {};
  }
};

const getConfigurations = (configurations, service) => {
  return {
    headers: {
      ...getAuthHeader(service),
    },
    ...configurations,
  };
};
const getParams = (...params) => {
  return params.filter((param) => {
    return !!param;
  });
};

const api = (
  type,
  url,
  data = null,
  service = "other",
  configurations = {}
) => {
  const params = getParams(
    getBaseUrl(service) + url,
    data,
    getConfigurations(configurations, service)
  );
  return axios[type](...params)
    .then((response) => response.data)
    .catch(async (error) => {
      const originalRequest = error.config;
      if (error.response.status === 401) {
        originalRequest._retry = true;
        const access_token = await refreshAccessToken();
        localStorage.setItem("token", "Bearer " + access_token.data.jwtToken);
        originalRequest.headers.Authorization =
          "Bearer " + access_token.data.jwtToken;
        window.location.reload();
        return axiosApiInstance(originalRequest);
      } else if (error.response.status === 403) {
        const currentRoute = router.currentRoute;
        router.push({
          path: "/403",
          query: { redirect: `${currentRoute.path}${currentRoute.hash}` },
        });
      } else if (
        error.response.status === 400 &&
        url.includes("refresh-token")
      ) {
        localStorage.removeItem("refreshToken");
        localStorage.removeItem("token");
        localStorage.removeItem("userProfile");
        window.location.href = "/";
      } else if (error.response.status === 409) {
        toastr.warning(error.response.data.message);
        return Promise.resolve({
          status: "askMoreInfo",
        });
      } else {
        handleApiErrors(error.response.data);
        return Promise.reject();
      }
    });
};

const handleApiErrors = (error) => {
  handleGeneralError(error);
  handlePayloadValidationErrors(error);
};

const handleGeneralError = (error) => {
  if (error.message) {
    toastr.error(error.message);
  }
  if (error.statusCode === 401) {
    this.$router.push("/logout");
  }
};

const handlePayloadValidationErrors = (error) => {
  if (error.errors) {
    const payLoadValidationErrors = error.errors;
    for (const payLoadValidationError of payLoadValidationErrors) {
      const errorMessage = payLoadValidationError.error;
      toastr.error(errorMessage);
    }
  }
};

export default api;
