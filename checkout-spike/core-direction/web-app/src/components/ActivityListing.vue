<template>
  <main id="main">
    <listing-filter @search="simpleSearch" />
    <DateFilterActivityListing :coming-days="upComingDays" :currentDate="currentDate"
                               @dateFilter="applySingleFilterDate"
    />
    <AdvanceFilters @filter="applyFilter" @reset="resetFilter" />
    <section id="activity-listing">
      <div class="custom-container">
        <div v-if="isFilterApplied" class="activity-listing-outer-box">
          <div class="search-results-box">
            <p class="search-results-title">
              Search Results
            </p>
            <p class="search-results-desc">
              <information-icon-tooltip />
              Currently no filters have been applied. Please filter through “Advance Filters” to get personalized
              results.
            </p>
          </div>
        </div>
        <div v-if="!isFilterApplied" class="flex items-center justify-between flex-wrap mb-4">
          <p class="search-results-title">
            Search Results
          </p>
          <button class="btn-reset-filters flex items-center" @click="resetFilter">
            <span class="svg" /> Reset Filters
          </button>
        </div>
        <NoActivityListingFound v-if="activities.length === 0 && pageLoad" />
      </div>

      <div class="filter-items">
        <SearchActivities v-if="activities.length > 0" :activities="activities"
                          @changeActivityStatus="updateActivityStatus"
        />
      </div>
    </section>
  </main>
</template>

<script>
import ListingFilter from "../partials/listing-filter";
import toastr from "toastr";
import "swiper/css/swiper.css";
import AdvanceFilters from "../partials/AdvanceFilters";
import SearchActivities from "../partials/SearchActivities";
import {removeAllActiveClassesFromCategories, updateMetaInformation} from "../utils";
import DateFilterActivityListing from "./DateFilterActivityListing";
import moment from "moment";
import {getMissingDatesBetweenTwoDates, getUpcomingDaysUpto} from "../common/utilities/date-utility";
import NoActivityListingFound from "./NoActivityListingFound";
import {fetchActivitiesForListing} from "@/apiManager/activities";
import InformationIconTooltip from "@/svgs/information-icon-tooltip";
import updateActivityStatusMixin from "@/mixin/updateActivityStatusMixin";

export default {
  name: "ActivityListing",
  mixins: [updateActivityStatusMixin],
  components: {
    InformationIconTooltip,
    NoActivityListingFound,
    DateFilterActivityListing,
    SearchActivities,
    AdvanceFilters,
    ListingFilter,
  },
  data() {
    return {
      activities: [],
      isLogin: false,
      swiperOptions: {
        slidesPerView: "auto",
      },
      currentDate: "",
      pageLoad: false,
      isFilterApplied: true,
      message: "We’re sorry, but we have found no activities that match your search. Please select a different date and add activities and zones to your filter and search again.",
      upComingDays: [],
    };
  },
  mounted() {
    this.upComingDays = getUpcomingDaysUpto(6);
    updateMetaInformation("Activity Search - Find your inspiration | Core Direction", "Fitness activities, fitness classes, yoga classes, free class", "Find your inspiration: Search, browse & book activities from our partner network.", "Activity Search - Find your inspiration | Core Direction", "Find your inspiration: Search, browse & book activities from our partner network", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/activity-listing");

  },
  created() {
    this.getActivitiesList();
  },
  methods: {
    fetchActivities(payload, isFilterApplied = false) {
      fetchActivitiesForListing(payload)
        .then(response => {
          this.activities = response;
          this.pageLoad = true;
          this.isFilterApplied = isFilterApplied;
        })
        .catch(error => {
          toastr.error(error);
        });
    },

    getActivitiesList() {
      this.fetchActivities({"zone_ids": [], "activity_type_ids": []});
    },

    simpleSearch(search) {
      let payload = search;
      this.activities = [];
      this.fetchActivities(payload, false);
    },
    async applySingleFilterDate(date) {
      try {
        this.currentDate = date;
        const dateInterval = this.getDateInterval(date);
        const payload = this.createPayload([dateInterval]);
        this.isFilterApplied = false;
        await this.applyFilter(payload);
      } catch (error) {
        toastr.error("Failed to apply single filter date:", error);
      }
    },
    getDateInterval(date) {
      const formattedDate = moment(date).format("YYYY-MM-DD");
      return {"start_date": formattedDate, "end_date": formattedDate};
    },
    createPayload(date_intervals) {
      return {
        date_intervals,
        "zone_ids": [],
        "activity_type_ids": []
      };
    },
    applyFilter(payload) {
      let {date_intervals} = payload;
      if (date_intervals.length && date_intervals[0].start_date != date_intervals[0].end_date) {
        this.upComingDays = [];
        date_intervals.forEach((date) => {
          this.upComingDays.push(...getMissingDatesBetweenTwoDates(date.start_date, date.end_date));
        });
      }
      this.activities = [];
      if (!date_intervals?.length) {
        delete payload.date_intervals;
      }
      this.fetchActivities(payload, false);
    },
    resetFilter() {
      removeAllActiveClassesFromCategories();
      this.currentDate = "";
      this.fetchActivities({"zone_ids": [], "activity_type_ids": []}, true);
    },
  }

};
</script>

<style scoped>


</style>