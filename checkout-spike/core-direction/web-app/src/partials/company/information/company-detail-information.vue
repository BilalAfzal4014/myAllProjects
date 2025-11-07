<template>
  <div class="custom-container">
    <div class="business-profile-info-box">
      <about-us :activitiesList="activitiesList" :biography="biography" :address="address" :googlemap="googlemap" :challengesList="challengesList"
                :companyId="companyId"
                :galleryList="galleryList" :groupsList="groupsList" :offerList="offerList"
                :packagesList="packagesList"
      />
    </div>
  </div>
</template>

<script>
import AboutUs from "./about-us";

export default {
  name: "CompanyDetailInformation",
  components: {AboutUs},
  props: {
    googlemap: {
      type: String,
      default: "",
    },
    biography: {
      type: String,
      default: "",
    },
    address: {
      type: String,
      default: "",
    },
    companyId: {
      type: Number,
      default: null,
    },
    groupsList: {
      type: Array,
      default: () => [],
    },
    challengesList: {
      type: Array,
      default: () => [],
    },
    packagesList: {
      type: Array,
      default: () => [],
    },
    galleryList: {
      type: Array,
      default: () => [],
    },
    offerList: {
      type: Array,
      default: () => [],
    },
    activitiesList: {
      type: Array,
      default: () => [],
    }
  },

};
</script>

<style scoped>
@media screen and (max-width: 767px) {
  #business-profile .business-profile-info-box {
    margin-top: 50px !important;
  }
}
@media screen and (max-width: 767px) {
  #business-profile .business-profile-info-box {
    margin-top: 20px !important;
  }
}
</style>