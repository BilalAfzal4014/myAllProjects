<template>
  <div class="business-profile-about-us-box">
    <AccordionList :activitiesList="activitiesList" :address="address" :googlemap="googlemap" :biography="biography" :challengesList="challengesList"
                   :companyId="companyId" :galleryList="galleryList" :groupsList="groupsList"
                   :offerList="offerList"
                   :packagesList="packagesList"
    />
  </div>
</template>

<script>
import AccordionList from "@/components/BusinessProfile/Accordion/AccordionList.vue";

export default {
  name: "AboutUs",
  components: {
    AccordionList,
  },
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
  }
};
</script>

<style scoped>

</style>