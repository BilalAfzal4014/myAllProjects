import loginComponent from '../components/unAuthenticatedComponents/login';

import forgotPasswordComponent from '../components/unAuthenticatedComponents/forgotPassword';

import signUpComponent from '../components/unAuthenticatedComponents/signUp';

import dashboardComponent from '../components/authenticatedComponents/dashboard';

import dashboardStatsComponent
    from '../components/authenticatedComponents/dashboardChildComponents/dashboardStats/dashboardStats';

import appListingComponent from '../components/authenticatedComponents/dashboardChildComponents/app/appListing';

import importListingComponent
    from '../components/authenticatedComponents/dashboardChildComponents/import/importListing';
import queueListing
    from '../components/authenticatedComponents/dashboardChildComponents/campaign/queueListing';

import uploadComponent
    from '../components/authenticatedComponents/dashboardChildComponents/import/upload';

import companyListingComponent
    from '../components/authenticatedComponents/dashboardChildComponents/company/companyLisitng';

import companyEdit
    from '../components/authenticatedComponents/dashboardChildComponents/company/companyEdit';

import companyUserListing
    from '../components/authenticatedComponents/dashboardChildComponents/company/companyUserListing';

import newsFeedListing
    from '../components/authenticatedComponents/dashboardChildComponents/newsfeed/newsFeedListing';

import locationListing
    from '../components/authenticatedComponents/dashboardChildComponents/location/locationListing';

import createLocation
    from '../components/authenticatedComponents/dashboardChildComponents/location/createLocation';

import galleryListing
    from '../components/authenticatedComponents/dashboardChildComponents/gallery/galleryListing';

import userStats
    from '../components/authenticatedComponents/dashboardChildComponents/company/companyUserStats';

import campaignStats
    from '../components/authenticatedComponents/dashboardChildComponents/campaign/campaignStats';

/****Lazy LOAD *****/
const lookUpListingComponent = r => require.ensure([], () => r(require('../components/authenticatedComponents/dashboardChildComponents/lookUp/lookUpListing')));

const segmentListingComponent = r => require.ensure([], () => r(require('../components/authenticatedComponents/dashboardChildComponents/segment/segmentListing')));
/****Lazy LOAD *****/

import segmentCreationComponent
    from '../components/authenticatedComponents/dashboardChildComponents/segment/segmentCreation';

import campaignListingComponent
    from '../components/authenticatedComponents/dashboardChildComponents/campaign/campaignListing';

import campaignCreationComponent
    from '../components/authenticatedComponents/dashboardChildComponents/campaign/campaignCreation';

import notFoundComponent from '../components/otherComponents/notFound/notFound';

import languageLisiting from '../components/authenticatedComponents/dashboardChildComponents/language/languageLisiting';

import attributeListing
    from '../components/authenticatedComponents/dashboardChildComponents/attribute/attributeLisiting';

import newsfeedCreationComponent
    from '../components/authenticatedComponents/dashboardChildComponents/newsfeed/NewsFeedCreation';
import cacheViewer from "../components/authenticatedComponents/dashboardChildComponents/cache/cacheViewer";
import CampaignCappingSettings
    from "../components/authenticatedComponents/dashboardChildComponents/campaign/CampaignCappingSettings";
import newsFeedStats
    from '../components/authenticatedComponents/dashboardChildComponents/newsfeed/newsFeedStats';
import packageListing from "../components/authenticatedComponents/dashboardChildComponents/package/package";


export default [
    {
        path: "/",
        meta: {
            auth: false,
            route: "both"
        },
    },
    {
        path: "/login",
        component: loginComponent,
        meta: {
            auth: false,
            route: "both"
        },
    },
    {
        path: "/forgot-password",
        component: forgotPasswordComponent,
        meta: {
            auth: false,
            route: "both"
        },
    },
    {
        path: "/sign-up",
        component: signUpComponent,
        meta: {
            auth: false,
            route: "both"
        },
    },
    {
        path: "/dashboard",
        component: dashboardComponent,
        meta: {
            auth: true,
            route: "both"
        },
        children: [
            {
                path: "/dashboard/dashboard-stats",
                component: dashboardStatsComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/lookup-listing",
                component: lookUpListingComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/app-listing",
                component: appListingComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/import-listing",
                component: importListingComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/attribute/import-attribute-data",
                component: uploadComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/attribute-listing",
                component: attributeListing,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/segment-listing",
                component: segmentListingComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/segment-create",
                component: segmentCreationComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/segment-edit/:id",
                component: segmentCreationComponent,
                beforeEnter: (to, from, next) => {
                    if (to.params.id) {
                        next();
                    } else {
                        next("/dashboard/segment-create");
                    }
                },
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/campaign/capping-settings",
                component: CampaignCappingSettings,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/campaign-listing",
                component: campaignListingComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/campaign-create",
                component: campaignCreationComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/campaign-edit/:action/:id",
                component: campaignCreationComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
                beforeEnter: (to, from, next) => {
                    if ((to.params.action == "edit" || to.params.action == "view") && to.params.id) {
                        next();
                    } else {
                        next("/dashboard/campaign-listing");
                    }
                },
            },
            {
                path: "/dashboard/campaign-stats/:id",
                component: campaignStats,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/language-listing",
                component: languageLisiting,
                meta: {
                    auth: true,
                    route: "admin"
                },
            },
            {
                path: "/dashboard/company-listing",
                component: companyListingComponent,
                meta: {
                    auth: true,
                    route: "admin"
                },
            },
            {
                path: "/dashboard/company-edit/:id",
                component: companyEdit,
                meta: {
                    auth: true,
                    route: "both"
                },
            },
            {
                path: "/dashboard/company-userListing",
                component: companyUserListing,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/newsFeed-Listing",
                component: newsFeedListing,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/location-Listing",
                component: locationListing,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/location-create",
                component: createLocation,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/edit-location/:id",
                component: createLocation,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/gallery-listing",
                component: galleryListing,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/user-stats/:id",
                component: userStats,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/newsfeed-stats/:id",
                component: newsFeedStats,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/newsfeed-create",
                component: newsfeedCreationComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/newsfeed-edit/:id",
                component: newsfeedCreationComponent,
                meta: {
                    auth: true,
                    route: "company"
                },
            },
            {
                path: "/dashboard/cache",
                component: cacheViewer,
                meta: {
                    auth: true,
                    route: "admin"
                },
            },
            {
                path: "/dashboard/campaign-queue-listing",
                component: queueListing,
                meta: {
                    auth: true,
                    route: "admin"
                }
            },
            {
                path: "/dashboard/package-listing",
                component: packageListing,
                meta: {
                    auth: true,
                    route: "admin"
                }
            }
        ]
    },
    {
        path: '*',
        component: notFoundComponent,
    },
];