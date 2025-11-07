<template>
  <section id="advance-filter">
    <div class="custom-container">
      <div class="advance-filter-menu-btn-box flex items-center justify-center">
        <button class="advance-menu-filter-btn flex items-center" @click="isFiltersShow = !isFiltersShow">
          Advance Filter
          <FilterUpArrow v-if="isFiltersShow" />
          <FilterDownArrow v-if="!isFiltersShow" />
        </button>
      </div>
      <div :class="{hidden:!isFiltersShow}" class="advance-filter-btn-outer-box">
        <div class="advance-filter-btn-inner-box">
          <BusinessFilters @clicked="businessTypeFilter" />
          <DeliveryTypeFilter @online="deliveryTypeFilterOnline" @physical="deliveryTypeFilterPhysical" />
          <PaymentTypeFilter @clicked="paymentTypeFilter" />
        </div>
        <ActivityTypeFilter @clicked="activityTypeFilter" />
        <CalendarFilter @applyFilter="applyFilter" @changed="getDateRange" @resetFilter="resetFilters" />
      </div>
    </div>
  </section>
</template>

<script>
import BusinessFilters from "@/partials/BusinessFilters";
import DeliveryTypeFilter from "@/partials/DeliveryTypeFilter";
import ActivityTypeFilter from "@/partials/ActivityTypeFilter";
import CalendarFilter from "../partials/CalendarFilter";
import FilterDownArrow from "@/svgs/FilterDownArrow";
import FilterUpArrow from "@/svgs/FilterUpArrow";
import moment from "moment";
import PaymentTypeFilter from "./PaymentTypeFilter";
import {removeAllActiveClassesFromCategories} from "../utils";

export default {
  name: "AdvanceFilters",
  components: {
    PaymentTypeFilter,
    FilterUpArrow,
    FilterDownArrow, CalendarFilter, ActivityTypeFilter, DeliveryTypeFilter, BusinessFilters
  },
  data() {
    return {
      isFiltersShow: false,
      activityTypeFilters: [],
      deliveryTypeFilters: [],
      businessTypeFilters: [],
      date_intervals: [],
      isPhysical: false,
      isOnline: false,
      isFree: null
    };
  },
  methods: {
    activityTypeFilter(value) {
      if (this.activityTypeFilters.indexOf(value) !== -1) {
        this.activityTypeFilters = this.activityTypeFilters.filter((item) => item != value);
      } else {
        this.activityTypeFilters.push(value);
      }
    },
    deliveryTypeFilterOnline(value) {
      this.isOnline = value;
    },
    deliveryTypeFilterPhysical(value) {
      this.isPhysical = value;
    },
    businessTypeFilter(value) {
      if (this.businessTypeFilters.indexOf(value) !== -1) {
        this.businessTypeFilters = this.businessTypeFilters.filter((item) => item != value);
      } else {
        this.businessTypeFilters.push(value);
      }
    },
    getDateRange(dateInterval) {
      this.date_intervals = [];
      dateInterval.forEach((date) => {
        this.date_intervals.push({
          "start_date": moment(date.startDate, "DD-MM-YYYY").format("YYYY-MM-DD"),
          "end_date": moment(date.endDate, "DD-MM-YYYY").format("YYYY-MM-DD"),
        });
      });
    },
    resetFilters(value) {
      removeAllActiveClassesFromCategories();
      this.businessTypeFilters = [];
      this.date_intervals = [];
      this.deliveryTypeFilters = [];
      this.activityTypeFilters = [];
      this.$emit("reset", "reset");
      this.isFiltersShow = false;
    },
    paymentTypeFilter(data) {
      this.isFree = data;
    },
    applyFilter() {
      let payload = {
        "date_intervals": this.date_intervals,
        "zone_ids": this.businessTypeFilters,
        "activity_type_ids": this.activityTypeFilters,
        "is_free": this.isFree
      };
      if (this.isPhysical && !this.isOnline) {
        payload["is_online"] = false;
      }
      if (!this.isPhysical && this.isOnline) {
        payload["is_online"] = true;
      }
      this.$emit("filter", payload);
      this.isFiltersShow = false;
    }


  }
};
</script>

<style scoped>
#advance-filter {
  margin-bottom: 47px;
}
</style>