<template>
  <div>
    <section id="filter">
      <div class="custom-container filter-box flex  mx-auto justify-between items-center rounded">
        <search-location :address="address" @changed="getLatitudeLongitude" />
        <select-category @changed="getCategoryId" />
        <div class="search-btn-box ml-4 lg:mb-0 mb-4">
          <button class=" rounded-full" @click="searchCompany">
            Search
          </button>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import SearchLocation from "./inputs/search-location";
import SelectCategory from "./inputs/select-category";
import toastr from "toastr";

export default {
    name: "HeroComponent",
    components: {
        SelectCategory,
        SearchLocation,
    },
    data() {
        return {
            categoryId: null,
            lat: null,
            lng: null,
            address: ""
        };
    },
    methods: {
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
            this.$router.push({
                name: "search",
                params: {id: this.categoryId},
                query: {address: this.address, lat: this.lat, lng: this.lng}
            });
        }
    }

};
</script>

<style scoped>

</style>