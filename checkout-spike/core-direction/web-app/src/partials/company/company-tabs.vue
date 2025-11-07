<template>
  <div class="tab-container">
    <div class="custom-container">
      <div class="business-profile-menu-tab-swiper-container">
        <swiper v-if="filteredTabs.length > 0" :options="companyTabsOption" class="swiper">
          <swiper-slide v-for="tab in filteredTabs" :key="tab.name">
            <button type="button" @click="getActiveTabName(tab.id)">
              <div :class="{ active: activeTab === tab.id }"
                class="business-profile-menu-tab-item flex items-center capitalize"
              >
                <component :is="tab.icon" :key="tab.name" />
                {{ tab.name }}
              </div>
            </button>
            <div slot="pagination" class="swiper-pagination" />
          </swiper-slide>
        </swiper>
      </div>
    </div>
  </div>
</template>

<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import InfoTabIcon from "@/svgs/tabs/info-tab-icon";
import ActivityTabIcon from "@/svgs/tabs/activity-tab-icon";
import PackageTabIcon from "@/svgs/tabs/package-tab-icon";
import GalleryTabIcon from "@/svgs/tabs/gallery-tab-icon";
import GroupTabIcon from "@/svgs/company/group-tab-icon";
import ChallengesTabIcon from "@/svgs/tabs/challenges-tab-icon";
import DealsTabIcon from "@/svgs/tabs/deals-tab-icon";

export default {
  name: "CompanyTabs",
  components: {
    GroupTabIcon,
    GalleryTabIcon,
    PackageTabIcon,
    ActivityTabIcon,
    InfoTabIcon,
    ChallengesTabIcon,
    DealsTabIcon,
    Swiper,
    SwiperSlide
  },
  props: {
    activeTab: {
      type: String,
      required: true
    },
    activityTab: {
      type: Boolean,
      required: true,
    },
    challengeTab: {
      type: Boolean,
      required: true,
    },
    dealsAndOffersTab: {
      type: Boolean,
      required: true,
    },
    galleryTab: {
      type: Boolean,
      required: true,
    },
    packagesTab: {
      type: Boolean,
      required: true,
    },
    info: {
      type: Boolean,
      required: true,
    },
    groups: {
      type: Boolean,
      required: true,
    }
  },
  computed: {
    token() {
      return this.$store.getters.getStoreTokenGetters;
    },
    filteredTabs() {
      return this.tabs.filter(tab => {
        if (tab.id === "challenges" && this.challengeTab) {
          return this.token;
        }
        return this.shouldShowTab(tab.id);
      });
    },
    tabVisibilityMap() {
      return {
        activities: this.activityTab,
        challenges: this.challengeTab,
        dealsAndOffers: this.dealsAndOffersTab,
        gallery: this.galleryTab,
        packages: this.packagesTab,
        info: this.info,
        groups: this.groups
      };
    },
  },
  data() {
    return {
      tabs: [
        {
          "id": "info",
          "name": "info",
          "icon": InfoTabIcon
        },
        {
          "id": "dealsAndOffers",
          "name": "deals & offers",
          "icon": DealsTabIcon
        },
        {
          "id": "activities",
          "name": "activities",
          "icon": ActivityTabIcon
        },
        {
          "id": "packages",
          "name": "packages",
          "icon": PackageTabIcon
        },
        {
          "id": "groups",
          "name": "groups",
          "icon": GroupTabIcon
        },
        {
          "id": "challenges",
          "name": "challenges",
          "icon": ChallengesTabIcon
        },
        {
          "id": "gallery",
          "name": "gallery",
          "icon": GalleryTabIcon
        },


      ],
      companyTabsOption: {
        slidesPerView: "auto",
        spaceBetween: 24,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        breakpoints: {
          1024: {
            slidesPerView: 5,
            spaceBetween: 34
          },
          768: {
            slidesPerView: 3,
            spaceBetween: 30
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          320: {
            slidesPerView: "auto",
            spaceBetween: 20
          }
        }
      },
      
    };
  },
  methods: {
    getActiveTabName(tabName) {
      this.$emit("setComponent", tabName);
    },
    shouldShowTab(tabId) {
      return this.tabVisibilityMap[tabId] || false;
    }
  },
};
</script>

<style lang="scss" scoped>
@media screen and (max-width: 767px) {
  #business-profile .tab-container {
    margin-bottom: 0 !important;
  }
}

.custom-container {
  width: 100%;
  max-width: calc(1240px + 40px);
  padding-left: 20px;
  padding-right: 20px;
  margin-left: auto;
  margin-right: auto;
}
.swiper-slide{
  @media screen and (max-width: 480px){
    width: 100% !important;
  }
 
}
#business-profile .business-profile-menu-tab-swiper-container {
  width: 100%;
  max-width: 100%;
  margin: 0 auto;
}
#business-profile .business-profile-menu-tab-item{
  padding: 16px 22px;
  @media screen and (max-width: 480px){
    padding: 16px 17px;
  }
}
</style>
