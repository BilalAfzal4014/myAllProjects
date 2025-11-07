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
        <filter-item :companies="favouriteListCompanies.companies" :is-layout-type-three="isLayoutTypeThree" />
      </div>
    </div>
  </section>
</template>

<script>
import GoBack from "@/partials/back-button";
import ThreeLayoutBox from "@/svgs/ThreeLayoutBox";
import FourLayoutBox from "@/svgs/FourLayoutBox";
import FilterItem from "@/partials/filter-item";
import {getUserFavouritesCompanies} from "@/apiManager/user";
import {updateMetaInformation} from "@/utils";

export default {
  name: "FavouriteCompanies",
  components: {FilterItem, FourLayoutBox, ThreeLayoutBox, GoBack},
  data() {
    return {
      isLayoutTypeThree: false,
      favouriteListCompanies: [],
    };
  },
  mounted() {
    updateMetaInformation("My Favourites | Core Direction", "", "Save your favourite partner profiles for quick access", "My Favourites | Core Direction", "Save your favourite partner profiles for quick access", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/favourite-companies");
  },
  methods: {
    threeLayoutBox() {
      this.isLayoutTypeThree = true;
    },
    fourLayoutBox() {
      this.isLayoutTypeThree = false;
    },
    getFavouriteCompanyPayload(offset = 0) {
      return {
        "limit": 30,
        "offset": offset
      };
    },
    getFavouriteCompanies() {
      getUserFavouritesCompanies(this.getFavouriteCompanyPayload()).then((response) => {
        this.favouriteListCompanies = response.data;
        this.getFavouriteCompanyWithOffset(this.favouriteListCompanies.total_companies);
      }).catch(error => toastr.error(error));
    },
    async getFavouriteCompanyWithOffset(totalCompanies) {
      let response;
      while (this.favouriteListCompanies?.companies.length < totalCompanies) {

        response = await this.getFavouriteCompany(
          this.getFavouriteCompanyPayload(this.favouriteListCompanies?.companies.length)
        );
        this.favouriteListCompanies.companies.push(...response.data.companies);
      }
    },
    async getFavouriteCompany() {
      return await getUserFavouritesCompanies(this.getFavouriteCompanyPayload(this.favouriteListCompanies.companies.length));
    },
  },
  created() {
    this.getFavouriteCompanies();
  }
};
</script>

<style scoped>

</style>