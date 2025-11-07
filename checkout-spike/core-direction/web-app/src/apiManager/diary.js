import api from "@/apiManager/apiManager";

export const getUserActivityDiary = (data) => {
  const url = "/v3/get-user-diary-list";
  return api("post", url, data, "activity");
};

export const getActivityDairyDetailBySlug = (data) => {
  const url = "/get-activity-diary-by-slug-url";
  return api("post", url, data, "activity");
};

export const cancelActivity = (id) => {
  const url = `/delete-activity/${id}`;
  return api("delete", url, "", "activity");
};

export const getUserActivityDiaryDetail = (slug) => {
  const url = "/get-activity-diary-by-slug-url/";
  const data = {
    slugUrl: slug,
  };
  return api("post", url, data, "activity");
};
