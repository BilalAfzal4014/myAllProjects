import api from "@/apiManager/apiManager";

export const getMyGroups = (data) => {
  const url ="/group/groups_by_user";
  return api("post", url, data, "group");

};

export const getGroupDetails = (data) => {
  const url ="/group/show";
  return api("post", url, data, "group");

};

export const joinUserGroup = (data) => {
  const url ="/groups/add_members_to_group";
  return api("post", url, data, "group");

};

export const leaveUserGroup = (data) => {
  const url ="/groups/leave_user_from_group";
  return api("post", url, data, "group");

};

export const getFilteredUserGroup = (data) => {
  const url ="/groups/search_group";
  return api("post", url, data, "group");

};

export const getGroupsByUsername = (data) => {
  const url ="/group/groups_by_username";
  return api("post", url, data, "group");
};

export const getBusinessGroups = (data) => {
  const url ="/group/business_group";
  return api("post", url, data, "group");
};