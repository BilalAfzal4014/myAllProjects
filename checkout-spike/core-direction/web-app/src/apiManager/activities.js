import api from "@/apiManager/apiManager";
import { transformObjectIntoQueryString } from "@/common/constants/constants";

export const getAllActivities = (data) => {
  const url = "/activity/get-all-activities";
  return api("post", url, data, "other");
};

export const getActivityType = () => {
  const url = "/filter/activity_type";
  return api("get", url, null, "other");
};

export const getCompanyActivity = (data) => {
  const url = "/company/get-company-detail";
  return api("post", url, data, "other");
};

export const createActivityDiary = (data) => {
  const url = "/create-activity-diary";
  return api("post", url, data, "activity");
};

export const sendActivityInvite = (data) => {
  const url = "/invite-friend";
  return api("post", url, data, "activity");
};
export const updateActivityInvite = (data) => {
  const url = "/update-activity-diary-invite-status";
  return api("patch", url, data, "activity");
};

export const joinActivityInvite = (data) => {
  const url = "/join-activity";
  return api("post", url, data, "activity");
};

export const fetchActivitiesForListing = (data) => {
  const baseUrl = "/all-activities";
  const queryString = transformObjectIntoQueryString(data);

  const url = queryString ? `${baseUrl}?${queryString}` : baseUrl;

  return api("get", url, null, "activity");
};
