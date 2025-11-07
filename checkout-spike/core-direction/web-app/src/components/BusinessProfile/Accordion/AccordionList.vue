<template>
  <div class="accordion-tabs">
    <div v-for="(item, index) in filteredAccordions" :key="item.name">
      <AccordionItem :isActive="item.isActive" :itemName="item.name" @isActive="toggleAccordion(index, $event)" />
      <component :is="item.isActive ? item.component :''" :address="address" :googlemap="googlemap" :biography="biography" :companyId="companyId"
                 :sliderData="item.data"
                 @navigateToTab="navigateToTab"
      />
    </div>
  </div>
</template>

<script>
import AccordionItem from "./AccordionItem.vue";
import SliderGroups from "../Groups/SliderGroups.vue";
import SliderOffers from "../DealsAndOffers/SliderOffers.vue";
import BusinessProfileActivitySlider from "@/components/BusinessProfileActivitySlider.vue";
import SliderPackages from "../Packages/SliderPackages.vue";
import SliderChallenges from "../Challenges/SliderChallenges.vue";
import SliderGallery from "../Gallery/SliderGallery.vue";
import {eventEmitter} from "@/eventEmitter";
import ProfileInfo from "@/components/BusinessProfile/Information/ProfileInfo.vue";

export default {
  name: "AccordionList",
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
  components: {
    AccordionItem,
    SliderOffers,
    BusinessProfileActivitySlider,
    SliderPackages,
    SliderGroups,
    SliderChallenges,
    SliderGallery,
    ProfileInfo
  },
  methods: {
    toggleAccordion(index, isActive) {
      const originalIndex = this.filteredAccordions[index].originalIndex;
      this.$set(this.activeStates, originalIndex, isActive);
    },
    navigateToTab(exploreTab) {
      eventEmitter.emit("onChangeTab", exploreTab);
    },

  },
  data() {
    return {
      activeStates: [],
    };
  },
  created() {
    this.activeStates = this.allAccordions.map(() => true);
  },
  computed: {
    allAccordions() {
      return [
        {
          isActive: this.activeStates[0],
          component: ProfileInfo,
          name: "Business Info",
          data: [{biography : this.biography, address : "address"}]
        },
        {
          isActive: this.activeStates[1],
          component: SliderOffers,
          name: "Deals & Offers",
          data: this.offerList
        },
        {
          isActive: this.activeStates[2],
          component: BusinessProfileActivitySlider,
          name: "Activities",
          data: this.activitiesList
        },
        {
          isActive: this.activeStates[3],
          component: SliderPackages,
          name: "Packages",
          data: this.packagesList
        },
        {
          isActive: this.activeStates[4],
          component: SliderGroups,
          name: "Groups",
          data: this.groupsList
        },
        {
          isActive: this.activeStates[5],
          component: SliderChallenges,
          name: "Challenges",
          data: this.challengesList
        },
        {
          isActive: this.activeStates[6],
          component: SliderGallery,
          name: "Gallery",
          data: this.galleryList
        }
      ];
    },
    filteredAccordions() {
      return this.allAccordions.map((item, index) => {
        return {
          ...item,
          originalIndex: index
        };
      }).filter(item => item.data && item.data.length > 0);
    },

  },
};
</script>

<style lang="scss" scoped>
  .accordion-tabs {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    row-gap: 32px;
    @media screen and (max-width: 480px){
      row-gap: 20px
    }
  }

</style>