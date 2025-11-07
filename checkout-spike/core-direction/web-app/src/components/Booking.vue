<template>
  <main id="main">
    <section id="business-profile">
      <company-header :company-banner="companyBanner" />
      <company-information :address="address" :company-logo="companyLogo" :company-name="companyName"
                           :companyId="companyId"
                           :googlemap="googlemap" :isCorporate="isCorporate"
                           :phone="phone"
                           :website="website"
      />
      <company-tabs :active-tab="activeTabHeading" :activityTab="activityTab" :challengeTab="challengeTab"
                    :dealsAndOffersTab="dealsAndOffersTab"
                    :galleryTab="galleryTab"
                    :groups="groupsList.length > 0"
                    :info="true"
                    :packagesTab="packagesTab"
                    @setComponent="setActiveComponent"
      />
      <component :is="activeComponent" :activities="activities" :activitiesList="activitiesList" :address="address"
                 :biography="biography" :challengesList="challengesList"
                 :companyId="companyId" :company_slug="company_slug"
                 :galleryList="galleryList"
                 :googlemap="googlemap"
                 :groupsList="groupsList"
                 :offerLimit="DEALS_AND_OFFERS.LIMIT"
                 :offerList="offerList"
                 :offerOffset="DEALS_AND_OFFERS.OFFSET"
                 :packagesList="packagesList"
                 @reset="getCompanyActivities" @searched="getFilterObject"
                 @updateActivityStatus="changeActivityStatus"
      />
    </section>
  </main>
</template>

<script>
import BusinessProfileFilter from "../partials/BusinessProfileFilter";
import BookingGallery from "../partials/BookingGallery";
import * as toastr from "toastr";
import CompanyHeader from "../partials/company/company-header";
import CompanyInformation from "../partials/company/company-information";
import CompanyTabs from "../partials/company/company-tabs";
import PackageListing from "../partials/company/packages/package-listing";
import CompanyDetailInformation from "../partials/company/information/company-detail-information";
import Activities from "../partials/company/activities/activities";
import DealsAndOffers from "@/components/BusinessProfile/DealsAndOffers/AllOffers";
import {DEALS_AND_OFFERS} from "@/components/BusinessProfile/DealsAndOffers";
import CompanyGroup from "@/components/company/company-group";
import GroupChallenges from "@/components/company/GroupChallenges";
import updateActivityStatusMixin from "@/mixin/updateActivityStatusMixin";
import {getCompanyDetail} from "@/apiManager/company-detail";
import {NON_CORPORATE_MESSAGE} from "@/common/constants/constants";
import {getBusinessChallenges} from "@/apiManager/gamification";
import {getBusinessGroups} from "@/apiManager/groups";
import {getOffers} from "@/apiManager/offers";
import {eventEmitter} from "@/eventEmitter.js";
import {updateMetaInformation} from "@/utils";

export default {
  name: "Booking",
  mixins: [updateActivityStatusMixin],
  components: {
    CompanyGroup,
    Activities,
    CompanyDetailInformation,
    PackageListing,
    CompanyTabs,
    CompanyInformation,
    CompanyHeader,
    BookingGallery,
    BusinessProfileFilter,
    GroupChallenges,
    DealsAndOffers,

  },
  data() {
    return {
      isLogin: false,
      latitude: 0,
      longitude: 0,
      companyId: null,
      activities: [],
      companyBanner: "",
      website: "",
      phone: "",
      companyName: "",
      companyLogo: "",
      pageLoad: false,
      address: "",
      googlemap: "",
      biography: "",
      activeComponent: CompanyDetailInformation,
      company_slug: "",
      activeTabHeading: "info",
      DEALS_AND_OFFERS,
      groupsList: [],
      challengesList: [],
      packagesList: [],
      galleryList: [],
      offerList: [],
      activitiesList: [],
      activityTab: true,
      challengeTab: true,
      dealsAndOffersTab: true,
      galleryTab: true,
      packagesTab: true,
      isCorporate: false,
      pageTitle: "",

    };
  },
  computed: {
    companySlug() {
      return this.$route.params.slug;
    }
  },
  methods: {
    changeActivityStatus(data) {
      this.updateActivityStatus(data);
    },
    setActiveComponent(componentName) {
      this.activeComponent = this.getComponentName(componentName);
      this.activeTabHeading = componentName;
      window.location.hash = encodeURIComponent(componentName);
      ;
    },
    getComponentName(componentName) {
      return {
        activities: Activities,
        info: CompanyDetailInformation,
        packages: PackageListing,
        gallery: BookingGallery,
        groups: CompanyGroup,
        challenges: GroupChallenges,
        dealsAndOffers: DealsAndOffers,
      }[componentName];
    },

    async getCompanyDetail(slugOrPayload, type) {
      try {
        let payload;
        if (typeof slugOrPayload === "string") {
          payload = {slug: slugOrPayload, type};
        } else if (typeof slugOrPayload === "object") {
          payload = slugOrPayload;
          type = payload.type;
        }
        const response = await getCompanyDetail(payload);
        return response.data;
      } catch (error) {
        return error.message;
      }
    },

    getImageUrl(directory, filename) {
      return this.constants.getImageUrl(`${directory}/${filename}`);
    },

    async getCompanyActivities() {
      if (this.companySlug) {
        const activities = await this.getCompanyDetail(this.companySlug, "activities");
        this.activities = this.formatActivitiesList(activities);
        this.pageLoad = true;
      }
    },

    async getFilterObject(data) {
      const activities = await this.getCompanyDetail({...this.getFilterPayload(data)}, "activities");
      this.activities = this.formatActivitiesList(activities);
    },
    formatActivitiesList(activities) {
      return activities.map(activity => {
        activity.list = activity.list.map(list => {
          list.facilityImage = this.getImageUrl("member", list.facilityImage);
          list.activityTypeImage = this.getImageUrl("activity", list.activityTypeImage);
          return list;
        });
        return activity;
      });
    },
    getFilterPayload(data) {
      const {
        activity_type_ids,
        zone_ids,
        start_date,
        end_date
      } = data;

      return {
        slug: this.companySlug,
        type: "activities",
        activity_type_ids,
        zone_ids,
        start_date,
        end_date
      };
    },

    async getCompanyBusinessInformation() {
      const response = await this.getCompanyDetail(this.companySlug, "basic-info");
      const {
        address,
        phone,
        company_banner,
        website,
        company_logo,
        company_name,
        latitude,
        longitude,
        company_slug,
        company_id,
        activities,
        challenges,
        deal_offers,
        gallery,
        packages,
        company_profile,
      } = response;
      this.address = address;
      this.phone = phone;
      this.companyBanner = company_banner;
      this.website = website || "#";
      this.companyLogo = this.getImageUrl("member", company_logo) + "?optimizer=image&format=webp&width=250&aspect_ratio=1:1&sharpen=true";
      this.companyName = company_name;
      this.googlemap = `https://maps.google.com/?q=${parseFloat(latitude)},${parseFloat(longitude)}`;
      this.company_slug = company_slug;
      this.companyId = company_id;
      this.isCorporate = company_profile === "Corporate" ? true : false;


      this.activityTab = activities;
      this.challengeTab = challenges;
      this.dealsAndOffersTab = deal_offers;
      this.galleryTab = gallery;
      this.packagesTab = packages;
      this.updateMetaData();


      if (this.$store.state.token) {
        await this.getGroupsSlider();
        await this.getChallengesSlider();
      }

      await this.getOffersSlider();
    },
    async checkCorporateStatus() {
      if (this.companySlug) {
        const response = await this.getCompanyDetail(this.companySlug, "basic-info");
        if (response.hasOwnProperty("isEmployee") && response.isEmployee !== null) {
          toastr.error(NON_CORPORATE_MESSAGE);
          this.$router.push("/listing");
          return true;
        }
        return false;
      }
    },
    isLoggedIn() {
      return !!localStorage.getItem("token");
    },
    async getCompanyBiography() {
      if (this.companySlug) {
        try {
          const res = await getCompanyDetail({slug: this.companySlug, type: "biography"});
          if (res.statusCode === 200 && res?.data) {
            this.biography = res.data.biography;
          }
        } catch (e) {
          return e.message;
        }
      }
    },

    async getChallengesSlider() {
      try {
        const res = await getBusinessChallenges({profileId: this.companyId, offset: 0, limit: 4,});
        if (res.statusCode === 200 && res?.data?.challenges?.length > 0) {
          this.challengesList = res.data.challenges;
        }
      } catch (e) {
        return e.message;
      }
    },

    async getGroupsSlider() {
      try {
        const res = await getBusinessGroups({company_id: this.companyId, limit: 4});
        if (res.statusCode === 200 && res?.data?.length > 0) {
          this.groupsList = res.data;
        }
      } catch (e) {
        return e.message;
      }
    },

    async getPackagesSlider() {
      if (this.companySlug) {
        try {
          const res = await getCompanyDetail({slug: this.companySlug, type: "packages", limit: 4});
          if (res.statusCode === 200 && res?.data?.length > 0) {
            this.packagesList = res.data;
          }
        } catch (e) {
          return e.message;
        }
      }
    },

    async getGallerySlider() {
      if (this.companySlug) {
        try {
          const res = await getCompanyDetail({slug: this.companySlug, type: "gallery", limit: 4});
          if (res.statusCode === 200 && res?.data?.length > 0) {
            this.galleryList = res.data;
          }
        } catch (e) {
          return e.message;
        }
      }
    },

    async getOffersSlider() {
      try {
        const res = await getOffers(this.companyId, 4, 0);
        if (res.statusCode === 200 && res?.data?.length > 0) {
          this.offerList = res.data;
        }
      } catch (e) {
        return e.message;
      }
    },

    async getActivitiesSlider() {
      if (this.companySlug) {

        try {
          const res = await getCompanyDetail({slug: this.companySlug, type: "activities", limit: 4});
          if (res.statusCode === 200 && res?.data?.length > 0) {
            res.data.forEach(item => {
              if (item?.list?.length > 0) {
                this.activitiesList = [...this.activitiesList, ...item.list];
              }
            });
          }
        } catch (e) {
          return e.message;
        }
      }

    },
    onHashChange() {
      const hash = window.location.hash.replace("#", "");
      if (hash) {
        this.setActiveComponent(hash);
      }
    },

    updateMetaData() {
      this.pageTitle = this.companyName || "Company Name";
      this.pageImage = this.companyBanner || "cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp";
      const pageUrl = window.location.href;
      updateMetaInformation(this.pageTitle + " | Core Direction", "", "View " + this.pageTitle + "’s profile, Browse activities and deals & offers", this.pageTitle + " | Core Direction", "View " + this.pageTitle + "’s profile, Browse activities and deals & offers", "https://cdn.coredirection.com/prod-media/images/member/" + this.pageImage + "?optimizer=image&format=webp&width=1200&quality=80", pageUrl);
    },
  },

  async created() {

    window.addEventListener("hashchange", this.onHashChange);
    this.isLogin = this.isLoggedIn();
    const isCorporate = await this.checkCorporateStatus();
    if (!isCorporate && this.companySlug) {
      await this.getCompanyBusinessInformation(),
      await Promise.all([
        this.getCompanyActivities(),
        this.getCompanyBiography(),
        this.getPackagesSlider(),
        this.getGallerySlider(),
        this.getActivitiesSlider(),
      ]);
    }

    eventEmitter.on("onChangeTab", (tab) => {
      this.activeComponent = this.getComponentName(tab);
      this.activeTabHeading = tab;
      window.location.hash = tab;
    });
  },
  beforeDestroy() {
    eventEmitter.off("onChangeTab");
  },
  mounted() {
    const hash = window.location.hash.replace("#", "");
    if (hash) {
      this.setActiveComponent(hash);
    }
  },
  destroyed() {
    window.removeEventListener("hashchange", this.onHashChange);
  },
};
</script>

<style lang="scss" scoped>
.custom-container {
  width: 100%;
  max-width: calc(1240px + 40px);
  padding-left: 20px;
  padding-right: 20px;
  margin-left: auto;
  margin-right: auto;
}

#business-profile .business-profile-info-box {
  padding-bottom: 50px;
  margin-top: -16px;
}
</style>