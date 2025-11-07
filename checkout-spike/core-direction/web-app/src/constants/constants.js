export default {
  frontendUrl: process.env.VUE_APP_FRONTEND_URL,
  baseUrl: process.env.VUE_APP_BASE_URL || "http://localhost:4001/v1",
  signupApi: "/signup",
  basicAuth: {
    username: process.env.VUE_APP_BASIC_AUTH_USERNAME,
    password: process.env.VUE_APP_BASIC_AUTH_PASSWORD,
  },
  activityDiaryMicroServiceUrl: process.env.VUE_APP_ACTIVITY_DIARY_BASE_URL,
  loginApi: "/auth/login",
  getCategories: "/filter/profile_category",
  getCompany: "/company/get-companies",
  assetPrefix: process.env.VUE_APP_ASSETS_PREFIX,
  optimiseAssetsPrefix: process.env.VUE_APP_IMAGE_OPTIMIZATION_URL,
  cdnAssetPrefix: process.env.VUE_APP_ASSETS_PREFIX,
  microServiceAssetPrefix:
    process.env.VUE_APP_ASSETS_MEDIA_MICRO_SERVICE_PREFIX,
  getProfile: "/user/profile",
  updateUserProfile: "/user/update-profile",
  profileSetting: "/profile-settings",
  getAllActivity: "/activity/get-all-activities",
  getCompanyActivity: "/company/get-company-detail",
  getBusinessBasicInfo: "/company/get-company-detail",
  getBusinessGallery: "/company/get-company-detail",
  socialLogin: "/login/SOCIAL",
  socialLoginOnServer: "/login/social/oauth",
  biography: "/company/get-company-detail",
  getActivityType: "/filter/activity_type",
  getZoneType: "/filter/zone",
  bookActivity: "/activity/book-activity",
  bulkBookActivity: "/booking/bulk-user-book-activity",
  bookingHistory: "/booking/get-user-booking-history",
  upComingBooking: "/booking/get-user-upcoming-booking",
  getCalendarBooking: "/booking/get-user-calendar-booking",
  cancelActivity: "/booking/cancel-activity",
  cancelReserveActivity: "/activity/cancel-reserve-activity",
  checkedIn: "/booking/checkin",
  forgetPassword: "/forgot-password",
  memberPackage: "/package/get-member-packages",
  getProvider: "/company/get-facility-companies",
  getPurchasedPackageOfActivity: "/package/get-class-member-packages",
  getAssignedPackageToActivity: "/activity/get-packages",
  redeemPackage: "/redeem/redeem-key",
  bookActivityForUser: "/activity/book-activity",
  bookActivityForUsers: "/activity/book-multiple-activity",
  purchasePackage: "/package/purchase-package",
  purchasePackages: "/package/purchase-multiple-packages",
  getAllCardsOfAUser: "/cards/user-cards",
  getPackage: "/package/get-package",
  getCardListing: "/cards/user-cards",
  deleteCard: "/cards/delete-user-card/",
  contactUs: "/general/contact-us",
  getFavouriteCompanies: "/user/favourite-companies-list",

  getUrl: function (route) {
    return this.baseUrl + this[route];
  },
  getImageUrl: function (image) {
    if (image !== null && image !== undefined) {
      if (image.includes("media-micro-service")) {
        return this.microServiceAssetPrefix + image;
      } else {
        return this.assetPrefix + image;
      }
    } else {
      return this.assetPrefix + image;
    }
  },
  getImageOptimisedUrl: function (image) {
    if (image !== null && image !== undefined) {
      if (image.includes("media-micro-service")) {
        return this.microServiceAssetPrefix + image;
      } else {
        return this.optimiseAssetsPrefix + image;
      }
    } else {
      return this.assetPrefix + image;
    }
  },

  async askUserLocation() {
    return new Promise((resolve, reject) => {
      navigator.geolocation.getCurrentPosition(
        position => {
          resolve({
            latitude: position.coords.latitude,
            longitude: position.coords.longitude
          });
        },
        error => {
          resolve({
            latitude: null,
            longitude: null
          });
        },
      );
    });
  }
};
