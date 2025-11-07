export const PHONE_NUMBER_REGEX = /^\+?[1-9]\d{1,14}$/;
export const COMPANIES_LIMIT = 200;
export const USER_GROUPS_LIMIT = 6;
export const NOTIFICATION_LIMIT = 6;
export const FAVOURITE_COMPANIES_LIMIT = 3;
export const NOTIFICATION_STATUS = {
  READ: "read",
  UNREAD: "unread",
  ALL: "all",
};

export const BASE_URLS = {
  ASSETS_MEDIA_MICRO_SERVICE_PREFIX:
    process.env.VUE_APP_ASSETS_MEDIA_MICRO_SERVICE_PREFIX,
  ASSETS_PREFIX_ACTIVITY: process.env.VUE_APP_ASSETS_PREFIX_ACTIVITY,
  ASSETS_PREFIX_MEMBER: process.env.VUE_APP_ASSETS_PREFIX_MEMBER,
  ASSETS_PREFIX_FACILITY_GALLERY:
    process.env.VUE_APP_ASSETS_PREFIX_FACILITY_GALLERY,
  ASSETS_PREFIX: process.env.VUE_APP_ASSETS_PREFIX,
};

export const FOLLOW_REQUEST_STATUS = {
  ACCEPTED: "accepted",
  REJECTED: "rejected",
};

export const FOLLOW_REQUEST_MESSAGES = {
  ACCEPTED: "Friend request accepted",
  REJECTED: "Friend request declined",
  FOLLOWING: "New Following",
  FOLLOWER: "New Follower",
};

export const ACTIVITY_INVITE_MESSAGES = {
  ACCEPTED: "Invitation is accepted from",
  REJECTED: "Invitation is declined from",
};

export const NOTIFICATION_TYPE = {
  FOLLOW_REQUEST: "follow_request",
  FOLLOWING: "added_as_following",
  ACTIVITY_INVITE: "INVITE_TO_ACTIVITY",
  ACTIVITY_ACCEPTED: "ACTIVITY_INVITE_ACCEPTED",
  NEW_ACTIVITY_INVITE: "New Activity Invite",
  ACTIVITY_INVITE_REJECTED: "ACTIVITY_INVITE_REJECTED",
  LEAVE_ACTIVITY: "LEAVE_ACTIVITY",
  ACTIVITY_REMOVED: "ACTIVITY_REMOVED",
};

export const GROUP_TYPE = {
  ACTIVITY: "activity",
  BUSINESS: "business",
  CORPORATE: "corporate",
};

export const GROUP_ADVANCE_FILTER = {
  JOINED: "joined",
  POPULAR: "popular",
  ALL: "newest",
};

export const GROUP_CATEGORY_OPTIONS = {
  Businessess: "business",
  "Corporate & Brands": "corporate",
  "Activity Type": "activity",
};

export const PRIVACY_TYPE = {
  PUBLIC: "public",
  PRIVATE: "private",
};

export const USER_STATUS = {
  UNKNOWN: "unknown",
  ACCEPTED: "accepted",
  REQUESTED: "requested",
};
//Dubai Longitude and Latitude
export const MAP_CENTER = {
  lat: 25.2048493,
  lng: 55.2707828,
};
export const EXTERNAL_LINKS = {
  PRIVACY_POLICY: `${process.env.VUE_APP_FRONTEND_BASE_HOSTNAME}/privacy-policy`,
  TERMS_AND_CONDITIONS: `${process.env.VUE_APP_FRONTEND_BASE_HOSTNAME}/terms-and-conditions`,
  CONTACT_US: `${process.env.VUE_APP_FRONTEND_BASE_HOSTNAME}/contact-us`,
  USER_GUIDES: `${process.env.VUE_APP_FRONTEND_BASE_HOSTNAME}/user-guides-home`,
};
export const NON_AUTH_DROPDOWN_MENU = [
  {
    name: "Home",
    link: "/",
    code: "home",
    isExternal: false,
  },
  {
    name: "About us",
    link: process.env.VUE_APP_FRONTEND_BASE_HOSTNAME,
    code: "about_us",
    isExternal: true,
  },
  {
    name: "Contact Us",
    link: EXTERNAL_LINKS.CONTACT_US,
    code: "contact_us",
    isExternal: true,
  },
  {
    name: "User Guides",
    link: EXTERNAL_LINKS.USER_GUIDES,
    code: "user-guides-home",
    isExternal: true,
  },
  {
    name: "Privacy Policy",
    link: EXTERNAL_LINKS.PRIVACY_POLICY,
    code: "privacy_policy",
    isExternal: true,
  },
  {
    name: "Terms & Conditions",
    link: EXTERNAL_LINKS.TERMS_AND_CONDITIONS,
    code: "terms_and_conditions",
    isExternal: true,
  },
];

export const VIDEO_STATUS = {
  FAVORITE: "favourite",
  UNFAVORITE: "unfavourite",
};
export const ACTIVITY_TYPES = {
  ACTIVITY_TYPE_DIARY: "userActivityDairy",
  ACTIVITY_TYPE_BOOKING: "userBookings",
};

export const FAVOURITE_STATUS = {
  favourite: "favourite",
  unfavourite: "unfavourite",
};
export const FAVOURITE_MESSAGES = {
  favourite: "Facility has been added to the favourites.",
  unfavourite: "Facility has been removed from the favourites.",
};
export const SIGN_IN_MODAL_EVENT = "sign_in_modal";
export const SHOW_SIGN_IN_MODAL_MESSAGE = "show sign in modal";

export const transformObjectIntoQueryString = (params) => {
  const isValidValue = (value) =>
    value !== null && value !== undefined && value !== "";

  const isArrayOfObjects = (arr) =>
    Array.isArray(arr) &&
    arr.every((item) => typeof item === "object" && item !== null);

  return Object.entries(params)
    .flatMap(([key, value]) =>
      isValidValue(value)
        ? Array.isArray(value)
          ? isArrayOfObjects(value)
            ? `${encodeURIComponent(key)}=${encodeURIComponent(
              JSON.stringify(value)
            )}`
            : value.map(
              (val) => `${encodeURIComponent(key)}=${encodeURIComponent(val)}`
            )
          : `${encodeURIComponent(key)}=${encodeURIComponent(value)}`
        : []
    )
    .join("&");
};

export const NON_CORPORATE_MESSAGE =
  "You do not currently have access to this page. Please speak to your administrator to be added to this private profile.";
