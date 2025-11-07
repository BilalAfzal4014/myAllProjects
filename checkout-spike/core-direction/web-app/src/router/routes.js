import Landing from "../pages/HomePage";
import StyleGuide from "../views/StyleGuide";
import NoAccessPage from "../views/NoAccessPage";
import CorePremium from "@/views/CorePremium";
import SignupComponent from "../components/SignupComponent";
import Listing from "../components/location-listing";
import ActivityListing from "../components/ActivityListing";
import Calendar from "../components/Calendar";
import WalletComponent from "../pages/WalletPage";
import PageNotFound from "../components/PageNotFound";
import MainDashboard from "@/components/dashboard/main-dashboard";
import NonAuthDashboard from "@/components/dashboard/nonAuth-dashboard";
import AuthDashboard from "@/components/dashboard/auth-dashboard";
import CalendarFilter from "@/partials/CalendarFilter";
import CommunityPage from "@/components/community/community-page";
import SearchFriend from "@/components/community/search-friend";
import searchFriend from "@/components/community/search-friend";
import NotificationListing from "@/components/notification/NotificationListing";
import GroupDetails from "@/components/GroupDetails";
import GroupsFilterListing from "@/components/community/groups/GroupsFilterListing";
import PublicProfile from "@/components/user-profile-view/PublicProfile";
import PrivateProfile from "@/components/user-profile-view/PrivateProfile";
import VideoOnDemand from "@/components/video-on-demand/VideoOnDemand";
import CompleteUserProfile from "@/components/complete-user-profile";
import ChallengeDetail from "@/components/community/challenges/ChallengeDetail";
import AwsCognitoSocial from "@/components/AwsCognitoSocial";
import SetNewPassword from "@/components/set-new-password";
import PasswordSuccessfullySet from "@/components/password-successfully-set";
import ExternalLogin from "@/components/ExternalLogin";
import "@/plugins/vue_stripe_plugins.js";

////////////////LAZY LOADED COMPONENTS///////////////////////

const CategoryDetail = (r) =>
  require.ensure([], () => r(require("../components/category-detail")));
const ProfileSettings = (r) =>
  require.ensure([], () =>
    r(require("../components/user-profile/profile-settings"))
  );
const location = (r) =>
  require.ensure([], () => r(require("../components/location")));
const Booking = (r) =>
  require.ensure([], () => r(require("../components/Booking")));
const SeeAllBooking = (r) =>
  require.ensure([], () => r(require("../components/SeeAllBooking")));
const ContactUs = (r) =>
  require.ensure([], () => r(require("../components/ContactUs")));
const Faqs = (r) => require.ensure([], () => r(require("../components/Faqs")));
const PrivacyPolicy = (r) =>
  require.ensure([], () => r(require("@/components/PrivacyPolicy")));
const TermsConditions = (r) =>
  require.ensure([], () => r(require("@/components/TermsCondition")));
const SingleCompanyLocation = (r) =>
  require.ensure([], () => r(require("@/components/SingleCompanyLocation")));
const BookActivity = (r) =>
  require.ensure([], () =>
    r(require("../components/booking/book-activity/book-activity"))
  );
const BookMultipleActivity = (r) =>
  require.ensure([], () =>
    r(require("../components/booking/book-activity/book-multiple-activity"))
  );
const PurchasePackage = (r) =>
  require.ensure([], () =>
    r(require("../components/purchase-package/purchase-package"))
  );
const OurPartner = (r) =>
  require.ensure([], () => r(require("../components/OurPartner")));
const CookiesAndPolicy = (r) =>
  require.ensure([], () => r(require("../components/CookiesAndPolicy")));
const MainPage = (r) =>
  require.ensure([], () => r(require("../components/MainComponent")));
const EventConsultancy = (r) =>
  require.ensure([], () => r(require("../components/EventConsultancy")));
const CorporateWellness = (r) =>
  require.ensure([], () => r(require("../components/CorporateWellness")));
const FavouriteCompanies = (r) =>
  require.ensure([], () => r(require("../components/favourite-companies")));
const Corporates = (r) =>
  require.ensure([], () =>
    r(require("../components/CorporateUser/Corporates.vue"))
  );
const VideoOnDemandDetailPage = (r) =>
  require.ensure([], () =>
    r(require("../components/video-on-demand/video-on-demand-detail.vue"))
  );

const StripeCheckoutWallet = (r) =>
  require.ensure([], () => r(require("../components/StripeComponent")));
export default [
  {
    path: "/style",
    name: "StyleGuide",
    component: StyleGuide,
    meta: {
      auth: false,
    },
  },
  {
    path: "/stripe",
    name: "StripeCheckoutWallet",
    component: StripeCheckoutWallet,
    meta: {
      auth: false,
    },
  },
  {
    path: "/",
    name: "main-dashboard",
    component: MainDashboard,
    meta: {
      auth: false,
    },
    children: [
      {
        path: "/",
        name: "non-auth-dashboard",
        component: NonAuthDashboard,
        meta: {
          auth: false,
        },
        children: [
          {
            path: "/",
            name: "non-auth-landing",
            component: Landing,
            meta: {
              auth: false,
            },
          },
          {
            path: "/corporate/:slug",
            name: "corporate-invite",
            beforeEnter: (to, from, next) => {
              localStorage.setItem(
                "corporate_invite_slug",
                JSON.stringify(to.params.slug)
              );

              const token = localStorage.getItem("token");

              if (!token) {
                next("/signup");
              } else {
                next("/community");
              }
            },
            meta: {
              auth: false,
            },
          },
          {
            path: "/category-detail/:id",
            name: "CategoryDetail",
            component: CategoryDetail,
            props: true,
            meta: {
              auth: false,
            },
          },
          {
            path: "/favourite-companies",
            name: "favouriteCompanies",
            component: FavouriteCompanies,
            meta: {
              auth: true,
            },
          },
          {
            path: "/corporates",
            name: "corporates",
            component: Corporates,
            meta: {
              auth: true,
            },
          },
          {
            path: "/our-partner",
            name: "OurPartner",
            component: OurPartner,
            meta: {
              auth: false,
            },
          },
          {
            path: "/event-consultancy",
            name: "EventConsultancy",
            component: EventConsultancy,
            meta: {
              auth: false,
            },
          },
          {
            path: "/corporate-wellness",
            name: "CorporateWellness",
            component: CorporateWellness,
            meta: {
              auth: false,
            },
          },
          {
            path: "/our-services",
            name: "Landing",
            component: Landing,
            meta: {
              auth: false,
            },
          },
        ],
      },
      {
        path: "/contact-us",
        name: "contact-us",
        component: ContactUs,
        meta: {
          auth: false,
        },
      },
      {
        path: "/faqs",
        name: "Faqs",
        component: Faqs,
        meta: {
          auth: false,
        },
      },
      {
        path: "/privacy-policy",
        name: "PrivacyPolicy",
        component: PrivacyPolicy,
        meta: {
          auth: false,
        },
      },
      {
        path: "/terms-conditions",
        name: "TermsConditions",
        component: TermsConditions,
        meta: {
          auth: false,
        },
      }, //
      {
        path: "/cookies-policy",
        name: "CookiesAndPolicy",
        component: CookiesAndPolicy,
        meta: {
          auth: false,
        },
      }, //
      {
        path: "/",
        name: "auth-dashboard",
        component: AuthDashboard,
        meta: {
          auth: true,
        },
        children: [
          {
            path: "/listing",
            name: "Listing",
            component: Listing,
            meta: {
              auth: false,
            },
            props: {
              keyword: String,
            },
          },
          {
            path: "/community",
            name: "Community",
            component: CommunityPage,
            meta: {
              auth: true,
            },
          },
          {
            path: "/challenge/detail/:id",
            name: "ChallengeDetail",
            component: ChallengeDetail,
            meta: {
              auth: false,
            },
          },
          {
            path: "/profile/:username",
            name: "PublicProfile",
            component: PublicProfile,
            meta: {
              auth: true,
            },
            props: {
              type: String,
            },
          },
          {
            path: "/private/:username",
            name: "PrivateProfile",
            component: PrivateProfile,
            meta: {
              auth: true,
            },
          },
          {
            path: "/community/group/:id",
            name: "GroupDetail",
            component: GroupDetails,
            meta: {
              auth: true,
            },
          },
          {
            path: "/community/friend",
            name: "searchFriend",
            component: searchFriend,
            meta: {
              auth: true,
            },
            props: {
              type: String,
              username: String,
            },
          },
          {
            path: "/group-filter",
            name: "GroupFilterListing",
            component: GroupsFilterListing,
            meta: {
              auth: true,
            },
          },
          {
            path: "/notification-listing",
            name: "NotificationListing",
            component: NotificationListing,
            meta: {
              auth: true,
            },
          },
          {
            path: "/settings",
            name: "ProfileSettings",
            component: ProfileSettings,
            props: true,
            meta: {
              auth: true,
            },
          },
          {
            path: "/location",
            name: "location",
            component: location,
            meta: {
              auth: false,
            },
          },
          {
            path: "/booking/:slug",
            name: "Booking",
            component: Booking,
            meta: {
              auth: false,
            },
          },
          {
            path: "/company/location/:id",
            name: "SingleCompanyLocation",
            component: SingleCompanyLocation,
            meta: {
              auth: false,
            },
            props: {
              lat: String,
              lng: String,
            },
          },
          {
            path: "/search/:id",
            name: "search",
            component: CategoryDetail,
            props: {
              address: String,
              lat: String,
              lng: String,
            },
            meta: {
              auth: false,
            },
          },
          {
            path: "/activity-listing",
            name: "activity-listing",
            component: ActivityListing,
            meta: {
              auth: false,
            },
          },
          {
            path: "/calendar",
            name: "calendar",
            component: Calendar,
            meta: {
              auth: true,
            },
          },
          {
            path: "/on-demand/:category?",
            name: "VideoOnDemand",
            component: VideoOnDemand,
            meta: {
              auth: true,
            },
          },
          {
            path: "/on-demand/detail/:slug",
            name: "VideoOnDemandDetail",
            component: VideoOnDemandDetailPage,
            props: true,
            meta: {
              auth: true,
            },
          },
          {
            path: "/see-all-booking",
            name: "SeeAllBooking",
            component: SeeAllBooking,
            meta: {
              auth: false,
            },
            props: {
              type: String,
            },
          },
          {
            path: "/wallet",
            name: "WalletComponent",
            component: WalletComponent,
            meta: {
              auth: true,
            },
          },
          {
            path: "/book-activity/:activityId/:scheduleId",
            name: "book-activity",
            component: BookActivity,
            meta: {
              auth: false,
            },
            props: {
              lat: String,
              lng: String,
              facility: String,
            },
          },
          {
            path: "/book-multiple-activity/:activityId/:scheduleId",
            name: "book-multiple-activity",
            component: BookMultipleActivity,
            meta: {
              auth: false,
            },
            props: {
              lat: String,
              lng: String,
              facility: String,
            },
          },
          {
            path: "/purchase-package/:packageId",
            name: "purchase-package",
            component: PurchasePackage,
            meta: {
              auth: false,
            },
          },
          {
            path: "/corepremium",
            name: "CorePremium",
            component: CorePremium,
            meta: {
              auth: false,
            },
          },
          {
            path: "/403",
            name: "NoAccessPage",
            component: NoAccessPage,
            meta: {
              auth: false,
            },
          },
        ],
      },
    ],
  },
  {
    path: "/aws-cognito-social",
    name: "SignupComponentSocialCognito",
    component: AwsCognitoSocial,
    meta: {
      auth: false,
    },
  },
  {
    path: "/signup",
    name: "SignupComponent",
    component: SignupComponent,
    meta: {
      auth: false,
    },
  },
  {
    path: "/signup-social",
    name: "SignupComponentSocial",
    component: SignupComponent,
    meta: {
      auth: false,
    },
  },
  {
    path: "/external-login",
    name: "ExternalLogin",
    component: ExternalLogin,
    meta: {
      auth: false,
    },
  },
  {
    path: "/complete-profile-page",
    name: "CompleteProfilePage",
    component: CompleteUserProfile,
    meta: {
      auth: true,
    },
  },
  {
    path: "/about-us",
    name: "MainPage",
    component: MainPage,
    meta: {
      auth: false,
    },
  },
  {
    path: "/DatePickerPlugin",
    name: "DatePickerPlugin",
    component: CalendarFilter,
    meta: {
      auth: false,
    },
  },

  {
    path: "/search-friend",
    name: "SearchFriend",
    component: SearchFriend,
    meta: {
      auth: false,
    },
  },
  {
    path: "/set-new-password",
    name: "SetNewPassword",
    component: SetNewPassword,
    meta: {
      auth: false,
    },
  },
  {
    path: "/password-successfully-set",
    name: "PasswordSuccessfullySet",
    component: PasswordSuccessfullySet,
    meta: {
      auth: false,
    },
  },
  {
    path: "/calendar-filter",
    name: "CalendarFilter",
    component: CalendarFilter,
    meta: {
      auth: false,
    },
  },
  {
    path: "*",
    name: "PageNotFound",
    component: PageNotFound,
    meta: {
      auth: false,
    },
  },
];
