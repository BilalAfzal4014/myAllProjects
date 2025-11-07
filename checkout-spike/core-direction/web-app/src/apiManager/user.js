import api from "./apiManager";

const prefix = "/auth";
const user = "/user";

export const loginUser = (data) => {
  const url = "/login";
  return api("post", prefix + url, data, "auth");
};

export const getCorporateName = (slug) => {
  const url = `/user/corporate/${slug}`;
  return api("get", url, null, "user");
};

export const refreshAccessToken = () => {
  let userData = JSON.parse(localStorage.getItem("userProfile"));
  let data = {
    refreshToken: localStorage.getItem("refreshToken"),
    userName: userData.username,
  };
  const url = "/refresh-token";
  return api("post", prefix + url, data, "auth");
};

export const corporateEmployeeOnboarding = async (data) => {
  const url = "/corporate/onboard/" + data;
  return api("get", user + url, null, "user");
};

export const registerUser = (data) => {
  const url = "/register";
  return api("post", prefix + url, data, "auth");
};

export const externalLogin = (token) => {
  const url = "/validate-temp-token?token=" + token;
  return api("get", prefix + url, null, "auth");
};

export const getUserProfile = (username) => {
  const url = `/user/profile/{username}?username=${username}`;
  return api("get", url, null, "user");
};
const getUserName = () => {
  const userInformation = JSON.parse(localStorage.getItem("userProfile"));
  return userInformation.username;
};

export const updateProfile = (data) => {
  const url = "/user/update";
  return api("post", url, data, "user");
};

export const checkEmailExist = (data) => {
  const url = "/user/email-exist";
  return api("post", url, data, "user");
};

export const usernameExist = (data) => {
  const url = "/user/username-exist";
  return api("post", url, data, "user");
};

export const searchUserProfile = (data) => {
  const url = "/user/search-user-profile";
  return api("post", url, data, "user");
};

export const sendFollowRequest = (data) => {
  const url = "/user/follow-user";
  return api("post", url, data, "user");
};
export const sendUnfollowRequest = (id) => {
  const url = `/user/unfollow-user/${id}`;
  return api("delete", url, null, "user");
};

export const getConnectionList = (data) => {
  const url = "/user/connection-list";
  return api("post", url, data, "user");
};

export const updateFollowingStatus = (data) => {
  const url = "/user/update-following-request";
  return api("patch", url, data, "user");
};

export const getUserCommunityInformation = () => {
  const url = "/user/get-user-community";
  return api("get", url, null, "user");
};

export const getMyFollowings = () => {
  const url = "/user/following-list";
  return api("get", url, null, "user");
};

export const getMyFollowers = (queryParams = "") => {
  const url = `/user/follower-list${queryParams}`;
  return api("get", url, null, "user");
};

export const getUserFavouritesCompanies = (data) => {
  const url = "/user/favourite-companies-list";
  return api("post", url, data, "user");
};

export const checkUserExist = (data) => {
  const url = "/auth/social-login-check";
  return api(
    "get",
    url,
    {
      headers: {
        Authorization: "Bearer " + data.id_token,
      },
    },
    "auth"
  );
};

export const syncSocialMediaUser = (data) => {
  const url = "/auth/social-login";
  return api(
    "get",
    url,
    {
      headers: {
        Authorization: "Bearer " + data.id_token,
      },
    },
    "auth"
  );
};

export const forgotPassword = (data) => {
  const url = "/auth/forgot-password";
  return api("post", url, data, "auth");
};

export const recoverPassword = (data) => {
  const url = "/auth/reset-password";
  return api("post", url, data, "auth");
};

export const getUserCorporates = (limit) => {
  const url = `/user/list-corporates?limit=${limit}&offset=0`;
  return api("get", url, null, "user");
};

export const unSubscribeCorporateUser = (id) => {
  const url = `/user/corporate/unsubscribe/${id}`;
  return api("delete", url, null, "user");
};
export const userAttributesSetting = () => {
  const url = "/user/attributes";
  return api("get", url, null, "user");
};
