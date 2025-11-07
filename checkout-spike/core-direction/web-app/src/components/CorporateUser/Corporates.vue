<template>
  <section id="filter-items">
    <div class="custom-container">
      <div class="filter-items-outer-box">
        <div class="filter-items-header flex items-center justify-between">
          <go-back />
          <div class="layout-toggle-box">
            <button
              :class="`three-layout-box ${isLayoutTypeThree ? 'active-filter-button ' : 'non-active-filter-button'}`"
              type="button" @click="threeLayoutBox"
            >
              <ThreeLayoutBox />
            </button>
            <button
              :class="`four-layout-box ml-6 ${isLayoutTypeThree ? 'non-active-filter-button' : 'active-filter-button'}`"
              @click="fourLayoutBox"
            >
              <FourLayoutBox />
            </button>
          </div>
        </div>
        <filter-item :companies="corporates" :is-layout-type-three="isLayoutTypeThree" :show-fav="false" />
      </div>
    </div>
  </section>
</template>

<script>
import GoBack from "@/partials/back-button";
import ThreeLayoutBox from "@/svgs/ThreeLayoutBox";
import FourLayoutBox from "@/svgs/FourLayoutBox";
import FilterItem from "@/partials/filter-item";
import {getUserCorporates} from "@/apiManager/user";
import {updateMetaInformation} from "@/utils";

export default {
  name: "Corporates",
  components: {FilterItem, FourLayoutBox, ThreeLayoutBox, GoBack},
  data() {
    return {
      isLayoutTypeThree: false,
      corporates: [],
    };
  },
  mounted() {
    updateMetaInformation("My Corporates | Core Direction", "", "View your Corporates", "My Corporates | Core Direction", "View your Corporates", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/corporates");
  },
  methods: {
    threeLayoutBox() {
      this.isLayoutTypeThree = true;
    },
    fourLayoutBox() {
      this.isLayoutTypeThree = false;
    },
    getCorporatesList() {
      getUserCorporates(50).then(response => {
        this.corporates = response.data.companies;
      }).catch(error => {
        return error;
      });
    }
  },
  created() {
    this.getCorporatesList();
  }
};
</script>

<style scoped>

</style>
