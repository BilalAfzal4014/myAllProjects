<template>
  <section id="listing-filter"
           :class="`location-listing-filter ${$route.name === 'activity-listing' || isLogin ? 'margin-top-100' :''}`"
  >
    <div class="custom-container filter-box flex  mx-auto justify-between items-center rounded">
      <div class="input-field-search-activity-box mr-4">
        <input v-model="keyword" class="location rounded-full"
               placeholder="Search Activity, Provider Name.."
               type="text"
        >
        <magnify-icon />
      </div>
      <div class="input-field-category-box mr-2">
        <select-category @changed="getCategoryId" />
      </div>
      <div class="input-field-search-box ml-2">
        <search-location :address="address" @changed="setLocation" />
        <black-location-icon />
      </div>
      <div class="search-btn-box ml-4 lg:mb-0 mb-4">
        <button class=" rounded-full" @click="search">
          Search
        </button>
        <router-link class="btn-map" to="/location">
          <MapIcon />
        </router-link>
      </div>
    </div>
  </section>
</template>

<script>
import MagnifyIcon from "../svgs/magnify-icon";
import BlackLocationIcon from "../svgs/black-location-icon";
import MapIcon from "@/svgs/MapIcon";
import SearchLocation from "@/partials/inputs/search-location";
import SelectCategory from "@/partials/inputs/select-category";

export default {
    name: "ListingFilter",
    components: {SelectCategory, SearchLocation, MapIcon, BlackLocationIcon, MagnifyIcon},
    data() {
        return {
            profileCatId: "",
            keyword: "",
            latitude: "",
            longitude: "",
            address: ""

        };
    },
    computed: {
        isLogin() {
            return !!this.$store.getters.getStoreTokenGetters;
        },
    },
    methods: {
        getCategoryId(id) {
            this.profileCatId = id;
        },
        setLocation(location) {
            this.address = location.formatted_address;
            this.latitude = location.geometry.location.lat();
            this.longitude = location.geometry.location.lng();
        },

        createQuery() {
            return {
                keyword: this.keyword.trim(),
                lat: this.latitude,
                lng: this.longitude,
                profile_cat_id: this.profileCatId
            };
        },
        search() {
            this.$emit("search", this.createQuery());
        }
    }
};
</script>

<style scoped>
.margin-top-100 {
  margin-top: 100px;
}

#listing-filter {
  margin-bottom: 92px;
}

#listing-filter .filter-box {
  width: 100%;
  max-width: 1173px;
  padding: 42px 72px;
  margin-top: 62px;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  background-color: #FFFFFF;
  border-radius: 10px;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box {
    padding: 30px 15px;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin: 30px 0 0;
  }
}

#listing-filter .filter-box .input-field-search-box,
#listing-filter .filter-box .input-field-category-box,
#listing-filter .filter-box .input-field-search-activity-box {
  width: 100%;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-search-box,
  #listing-filter .filter-box .input-field-category-box,
  #listing-filter .filter-box .input-field-search-activity-box {
    margin: 0;
  }
}

#listing-filter .filter-box .input-field-search-box input,
#listing-filter .filter-box .input-field-search-box select,
#listing-filter .filter-box .input-field-category-box input,
#listing-filter .filter-box .input-field-category-box select,
#listing-filter .filter-box .input-field-search-activity-box input,
#listing-filter .filter-box .input-field-search-activity-box select {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  line-height: 18.5px;
  font-weight: 400;
  width: 100%;
  padding: 11px 13px 12px 18px;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  padding-right: 28px;
  background-color: #F1F1F1;
}

#listing-filter .filter-box .input-field-search-activity-box {
  max-width: 324px;
  position: relative;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-search-activity-box {
    max-width: 100%;
    margin: 0;
  }
}

#listing-filter .filter-box .input-field-search-activity-box svg {
  position: absolute;
  right: 13px;
  top: 13px;
  cursor: pointer;
}

#listing-filter .filter-box .input-field-search-activity-box svg,
#listing-filter .filter-box .input-field-search-activity-box path {
  fill: #222222;
}

#listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box {
  display: none;
  position: absolute;
  width: 100%;
  top: 0;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 20px 20px 11px 11px;
  z-index: 2;
  background-color: #FFFFFF;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box {
    border-radius: 11px 11px 20px 20px;
  }
}

#listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box .manual-location-field-header input {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  border-radius: 20px 20px 0 0;
  -webkit-border-radius: 20px 20px 0 0;
  -moz-border-radius: 20px 20px 0 0;
  -ms-border-radius: 20px 20px 0 0;
  -o-border-radius: 20px 20px 0 0;
  background-color: #FFFFFF;
}

#listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box .manual-location-field-header button {
  position: absolute;
}

#listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box .manual-location-field-body {
  padding-top: 22px;
  padding-bottom: 22px;
  padding-left: 8px;
  padding-right: 8px;
  margin-left: 10px;
  margin-right: 10px;
  border-top: 1px solid #222222;
}

#listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box .manual-location-field-body .location-list .location-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 18.75px;
  margin-bottom: 19px;
  cursor: pointer;
}

#listing-filter .filter-box .input-field-search-activity-box .manual-location-field-box .manual-location-field-body .location-list .location-item:last-child {
  margin-bottom: 0px;
}

#listing-filter .filter-box .input-field-search-box {
  max-width: 256px;
  position: relative;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-search-box {
    max-width: 48.5%;
    margin: 0;
  }

  #listing-filter .filter-box .input-field-search-box .input-field-search-box {
    max-width: 100%;
  }
}

@media (max-width: 389px) {
  #listing-filter .filter-box .input-field-search-box {
    max-width: 100%;
  }
}

#listing-filter .filter-box .input-field-search-box svg {
  position: absolute;
  right: 13px;
  top: 13px;
  cursor: pointer;
}

#listing-filter .filter-box .input-field-search-box .manual-location-field-box {
  display: none;
  position: absolute;
  width: 100%;
  top: 0;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 20px 20px 11px 11px;
  z-index: 2;
  background-color: #FFFFFF;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-search-box .manual-location-field-box {
    width: calc(100% + 38px);
    right: 0;
    border-radius: 11px 11px 20px 20px;
  }
}

@media (max-width: 389px) {
  #listing-filter .filter-box .input-field-search-box .manual-location-field-box {
    max-width: 100%;
  }
}

#listing-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-header input {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  font-style: italic;
  border-radius: 20px 20px 0 0;
  -webkit-border-radius: 20px 20px 0 0;
  -moz-border-radius: 20px 20px 0 0;
  -ms-border-radius: 20px 20px 0 0;
  -o-border-radius: 20px 20px 0 0;
  background-color: #FFFFFF;
}

#listing-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-header button {
  position: absolute;
}

#listing-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body {
  padding-top: 22px;
  padding-bottom: 22px;
  padding-left: 8px;
  padding-right: 8px;
  margin-left: 10px;
  margin-right: 10px;
  border-top: 1px solid #222222;
}

#listing-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list .location-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 18.75px;
  margin-bottom: 19px;
  cursor: pointer;
}

#listing-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list .location-item:last-child {
  margin-bottom: 0px;
}

#listing-filter .filter-box .input-field-category-box {
  max-width: 226px;
  position: relative;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-category-box {
    max-width: 48.5%;
    margin: 0;
  }

  #listing-filter .filter-box .input-field-category-box .input-field-category-box {
    max-width: 100%;
  }
}

@media (max-width: 389px) {
  #listing-filter .filter-box .input-field-category-box {
    max-width: 100%;
  }
}

#listing-filter .filter-box .input-field-category-box select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

#listing-filter .filter-box .input-field-category-box svg {
  position: absolute;
  right: 13px;
  top: 17px;
  cursor: pointer;
}

#listing-filter .filter-box .input-field-category-box svg,
#listing-filter .filter-box .input-field-category-box path {
  fill: #222222;
}

#listing-filter .filter-box .input-field-category-box .svg-category-icon.active {
  transform: rotate(180deg);
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
}

#listing-filter .filter-box .input-field-category-box .category-field-box {
  display: none;
  position: absolute;
  width: 100%;
  top: 0;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 20px 20px 11px 11px;
  z-index: 2;
  background-color: #FFFFFF;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-category-box .category-field-box {
    width: calc(100% + 38px);
    top: 2.9rem;
    border-radius: 11px 11px 20px 20px;
  }
}

@media (max-width: 389px) {
  #listing-filter .filter-box .input-field-category-box .category-field-box {
    max-width: 100%;
  }
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-category-box .category-field-box .category-field-header {
    display: none;
  }
}

#listing-filter .filter-box .input-field-category-box .category-field-box .category-field-header select {
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  font-style: italic;
  border-radius: 20px 20px 0 0;
  -webkit-border-radius: 20px 20px 0 0;
  -moz-border-radius: 20px 20px 0 0;
  -ms-border-radius: 20px 20px 0 0;
  -o-border-radius: 20px 20px 0 0;
  background-color: #FFFFFF;
}

#listing-filter .filter-box .input-field-category-box .category-field-box .category-field-header button {
  position: absolute;
}

#listing-filter .filter-box .input-field-category-box .category-field-box .category-field-body {
  padding-top: 22px;
  padding-bottom: 22px;
  padding-left: 8px;
  padding-right: 8px;
  margin-left: 10px;
  margin-right: 10px;
  border-top: 1px solid #222222;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-category-box .category-field-box .category-field-body {
    border-top: none;
  }
}

#listing-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list .category-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 18.75px;
  margin-bottom: 19px;
  cursor: pointer;
}

#listing-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list .category-item:last-child {
  margin-bottom: 0px;
}

#listing-filter .filter-box .search-btn-box {
  max-width: 183px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: 100%;
}

@media screen and (max-width: 768px) {
  #listing-filter .filter-box .search-btn-box {
    max-width: 100%;
    margin: 0;
  }
  #listing-filter .filter-box .search-btn-box .btn-map{
    margin-left: 10px;
  }
}

#listing-filter .filter-box .search-btn-box button {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  width: 100%;
  padding: 11px 16px;
  color: #FFFFFF;
  background-color: #690FAD !important;
}

#listing-filter .filter-box .search-btn-box .btn-map {
  color: #FFFFFF;
  background-color: #690FAD !important;
}

#listing-filter .filter-box .btn-map {
  min-width: 41px;
  min-height: 42px;
  max-width: 41px;
  max-height: 42px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  border-radius: 7px;
  padding: 0 !important;
}

#listing-filter .selected {
  opacity: 1;
  color: #690FAD;
  background: transparent;
}

#listing-filter.location-listing-filter {
  margin-bottom: 50px;
  position: relative;
  z-index: 2;
}

#listing-filter.location-listing-filter .filter-box {
  margin-top: -63px;
}

@media screen and (max-width: 991px) {
  #listing-filter.location-listing-filter .filter-box {
    margin-top: -10px;
  }
}

#listing-filter.on-demand-filter {
  margin-bottom: 60px;
}

#listing-filter.on-demand-filter .filter-box {
  padding: 41px 68px 42px 30px;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
}

@media screen and (max-width: 991px) {
  #listing-filter.on-demand-filter .filter-box {
    padding: 30px 15px;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin: 30px 0 0;
    row-gap: 15px;
    -webkit-column-gap: 10px;
    column-gap: 10px;
  }

  .location-listing-filter.margin-top-100 {
    margin-top: 40px;
  }
}

@media screen and (min-width: 992px) {
  #listing-filter.on-demand-filter .input-field-search-activity-box {
    max-width: 360px;
  }

  #listing-filter.on-demand-filter .input-field-category-box {
    max-width: 300px;
  }
}

#listing-filter.on-demand-filter .search-btn-box {
  max-width: 122px;
}

@media screen and (max-width: 768px) {
  #listing-filter.on-demand-filter .search-btn-box {
    max-width: 100%;
  }
}

#listing-filter.on-demand-filter ul li:hover {
  color: #690FAD;
}

#listing-filter.on-demand-filter .form-group input:checked + label:after {
  top: 1px;
}

#listing-filter.on-demand-filter input[type=checkbox] {
  display: none;
}

@media screen and (max-width: 991px) {
  #listing-filter .filter-box .input-field-search-box,
  #listing-filter .filter-box .input-field-category-box,
  #listing-filter .filter-box .input-field-search-activity-box {
    margin: 0 0 15px;
  }
}
</style>