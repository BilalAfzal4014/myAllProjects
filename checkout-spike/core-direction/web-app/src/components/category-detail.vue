<template>
  <div>
    <main id="main">
      <!-- see-all-filter-section -->
      <section id="see-all-filter">
        <div class="custom-container filter-box flex  mx-auto justify-between items-center rounded">
          <div class="input-field-search-activity-box mr-4">
            <search-location :address="address" @changed="getLatitudeLongitude" />
          </div>
          <div class="input-field-category-box mr-2">
            <select-category @changed="getCategoryId" />
          </div>
          <div class="search-btn-box ml-4 lg:mb-0 mb-4">
            <button class="rounded-full" type="button" @click="searchCompany">
              Search
            </button>
          </div>
        </div>
      </section>
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
            <filter-item :companies="companies" :is-layout-type-three="isLayoutTypeThree" />
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
import FilterItem from "../partials/filter-item";
import * as toastr from "toastr";
import ThreeLayoutBox from "@/svgs/ThreeLayoutBox";
import FourLayoutBox from "@/svgs/FourLayoutBox";
import GoBack from "@/partials/back-button";
import SelectCategory from "@/partials/inputs/select-category";
import SearchLocation from "@/partials/inputs/search-location";
import {getFilterCompany} from "@/apiManager/companies";

export default {
  name: "CategoryDetail",
  components: {
    SearchLocation,
    SelectCategory,
    GoBack,
    FourLayoutBox, ThreeLayoutBox, FilterItem
  },
  data() {
    return {
      isLogin: false,
      companies: [],
      isLayoutTypeThree: false,
      categoryId: "",
      lat: null,
      lng: null,
      address: "",
    };
  },
  async created() {
    this.isLogin = !!this.$store.getters.getStoreTokenGetters;
    this.address = this.$route.query.address ? this.$route.query.address : "";
    let userPosition = await this.constants.askUserLocation();
    this.lat = userPosition.latitude;
    this.lng = userPosition.longitude;

    let payload = {
      "profile_cat_id": this.$route.params.id ? this.$route.params.id : "",
      "keyword": "",
      "latitude": this.lat,
      "longitude": this.lng,
      "limit": 50,
      "offset": 0,
    };
    getFilterCompany(payload).then((response) => {
      this.companies = response.companies;
    }).catch((error) => {
      toastr.error(error.message);
    });
  },
  methods: {
    threeLayoutBox() {
      this.isLayoutTypeThree = true;
    },
    fourLayoutBox() {
      this.isLayoutTypeThree = false;
    },
    getCategoryId(id) {
      this.categoryId = id;
    },
    getLatitudeLongitude(places) {
      this.address = places.formatted_address;
      this.lat = places.geometry.location.lat();
      this.lng = places.geometry.location.lng();
    },
    searchCompany() {
      if (!this.categoryId && !this.lat) {
        toastr.error("Please either choose category type or location.");
        return;
      }
      if (this.$route.name === "search") {
        let payload = {
          "profile_cat_id": this.categoryId ? this.categoryId : "",
          "keyword": "",
          "lat": this.lat ? this.lat : "",
          "lng": this.lng ? this.lng : "",
        };
        this.companies = [];
        this.oldApi("post",
          this.constants.getUrl("getCompany"),
          payload, true
        ).then((response) => {
          this.companies = response.data.companies;
        }).catch((error) => {
          toastr.error(error.message);
        });
      } else {
        this.$router.push({
          name: "search",
          params: {id: this.categoryId},
          query: {q: "", lat: this.lat, lng: this.lng}
        }).catch((error) => {
          toastr.error(error.message);
        });
      }

    },

  }
};
</script>

<style scoped>

</style>