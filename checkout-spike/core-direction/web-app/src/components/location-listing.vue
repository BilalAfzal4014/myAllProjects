<template>
  <main id="main">
    <banner-component v-if="!isLogin" />
    <listing-filter @search="searchByFilter" />
    <section class="companies_listing">
      <corporates-list v-if="isLogin" />
      <favourite-companies-lists v-if="isLogin" :companies="favouriteListCompanies.companies"
                                 @removeFavouriteCompany="removeFavouriteCompany"
      />
      <cards :companies="showCompanies" />
      <data-not-found
        v-if="showDataNotFound"
        :message="message"
      />
    </section>
  </main>
</template>

<script>
import ListingFilter from "../partials/listing-filter";
import Cards from "../partials/cards";
import DataNotFound from "./DataNotFound";
import {getUserFavouritesCompanies} from "@/apiManager/user";
import {updateMetaInformation} from "@/utils";
import {getFilterCompany} from "@/apiManager/companies";
import {COMPANIES_LIMIT, FAVOURITE_COMPANIES_LIMIT} from "@/common/constants/constants";
import FavouriteCompaniesLists from "@/components/favourite-companies-lists";
import emitter from "tiny-emitter/instance";
import BannerComponent from "@/components/BannerComponent";
import CorporatesList from "@/components/CorporateUser/CorporatesList.vue";
import {mapGetters} from "vuex";
import {showWearableModal} from "@/router/componentHooks";
import * as toastr from "toastr";

export default {
  name: "LocationListing",
  components: {CorporatesList, BannerComponent, FavouriteCompaniesLists, DataNotFound, Cards, ListingFilter},
  data() {
    return {
      categories: [],
      companies: [],
      pageLoad: false,
      message: "We’re sorry, but we have found no activities that match your search. Please select a different date and add activities and zones to your filter and search again.",
      favoriteCompanyPayload: {
        "limit": 50,
        "offset": 0
      },
      favouriteListCompanies: [],
      count: 0,
      isStateHasData: false
    };
  },
  created() {
    emitter.on("favourite_button", () => {
      this.getFavouriteCompanies();
    });
    this.isStateHasData = !!this.$store.getters.getCompaniesList.length;
    this.getCompanyFilters();
    this.getFavouriteCompanies();
    if (this.$route.query.keyword) {
      this.$store.commit("setHeaderSearchKeywordMutation", this.$route.query.keyword);
      this.searchByFilter(this.$route.query.keyword);
      return false;
    } else if (this.$store.getters.getFilteredCompanies.length) {
      this.$store.commit("setFilteredCompanies", []);
    }

  },
  sockets: {
    client_get_company_list: function (data) {
      let resultCompanies = JSON.parse(data);
      this.companies[this.count].company = resultCompanies.data.companies;
      if (!this.isStateHasData)
        this.$store.commit("setCompaniesList", this.companies);
      this.count++;
      if (this.count < this.categories.length) {
        this.getCompanyList(this.count);
      } else {
        this.count = 0;
        if (this.isStateHasData) {
          this.$store.commit("setCompaniesList", this.companies);
        }
      }
    }
  },
  mounted() {
    updateMetaInformation("Discover & Book Fitness & Wellness Activities | Core Direction", "Fitness discounts, gym day pass, book yoga", "Browse our wellness partners: Discover, book and find discounts and offers from hundreds of our wellness partners", "Discover & Book Fitness & Wellness Activities | Core Direction", "Browse our wellness partners: Discover, book and find discounts and offers from hundreds of our wellness partners", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/listing");
  },
  computed: {
    ...mapGetters({
      getStoreHeaderSearchGetters: "getStoreHeaderSearchGetters",
      companiesList: "getCompaniesList",
      filteredCompanies: "getFilteredCompanies"
    }),
    isLogin() {
      return !!this.$store.getters.getStoreTokenGetters;
    },
    showDataNotFound() {
      return (this.filteredCompanies ? this.filteredCompanies.length === 0 : this.companiesList.length === 0) && this.pageLoad;
    },
    showCompanies() {
      return this.filteredCompanies.length ? this.filteredCompanies : this.companiesList;
    }
  },
  watch: {
    getStoreHeaderSearchGetters(newValue) {
      this.searchByFilter(newValue);
    }
  },
  methods: {
    removeFavouriteCompany(id) {
      this.favouriteListCompanies = this.favouriteListCompanies.filter(company => company.id !== id);
    },
    searchByFilter(data) {
      const payload = this.getSearchFilterPayload(data);
      if (this.isClearFilter(payload)) {
        this.clearFilteredCompanies();
        return;
      }

      this.companies = [];
      this.getFilteredCompanies(payload);
    },
    getSearchFilterPayload(data) {
      if (typeof data !== "object") return {keyword: data};

      return {
        profile_cat_id: data.profile_cat_id || "",
        keyword: data.keyword || "",
        lat: data.lat || "",
        lng: data.lng || "",
      };
    },
    isClearFilter(payload) {
      return !payload.profile_cat_id && !payload.lat && !payload.keyword;
    },
    clearFilteredCompanies() {
      this.$store.commit("setFilteredCompanies", []);
      this.companies = [];
      this.getCompanyFilters();
    },

    getFilteredCompanies(payload) {
      getFilterCompany(payload)
        .then((response) => {
          let categoriesWithCompanies = [];
          for (let category = 0; category < this.categories.length; category++) {
            categoriesWithCompanies[category] = {
              category_name: this.categories[category].title,
              link: `/category-detail/${this.categories[category].id}`,
              company: [],
              title: "See All"
            };
          }
          response.companies.forEach((company) => {
            for (let category = 0; category < this.categories.length; category++) {
              if (categoriesWithCompanies[category].category_name === company.title)
                categoriesWithCompanies[category].company.push(company);
            }
          });
          this.companies = categoriesWithCompanies;
          this.$store.commit("setFilteredCompanies", this.companies);
        })
        .catch((error) => toastr.error(error));
    },
    async getCompaniesPayload(category) {
      const userPosition = await this.constants.askUserLocation();
      return {
        profile_cat_id: this.categories[category].id,
        limit: COMPANIES_LIMIT,
        offset: 0,
        keyword: "",
        latitude: userPosition?.latitude ?? null,
        longitude: userPosition?.longitude ?? null,
      };
    },
    async getCompanyList(category) {
      const companyDetails = {
        company: [],
        category_name: this.categories[category].title,
        title: "See All",
        link: `/category-detail/${this.categories[category].id}`,
      };

      this.companies.push(companyDetails);
      this.$socket.emit("server_get_company_list", await this.getCompaniesPayload(category));
    },
    getCompanyFilters: async function () {
      const {data} = await this.oldApi(
        "get",
        this.constants.getUrl("getCategories"),
        true
      );
      this.categories = data;
      if (!this.$route.query.keyword) {
        await this.getCompanyList(this.count);
      }
    },
    getFavouriteCompanyPayload(offset = 0) {
      return {
        "limit": FAVOURITE_COMPANIES_LIMIT,
        "offset": offset
      };
    },
    getFavouriteCompanies() {
      if (!this.isLogin) return false;
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
  beforeRouteEnter(to, from, next) {
    next(vm => {
      showWearableModal(to, from, next, vm.$store);
    });
  },
};
</script>

<style scoped>
.companies_listing {
  margin-bottom: 100px;
}


</style>
