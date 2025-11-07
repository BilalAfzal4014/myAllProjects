<template>
  <div class="custom-container">
    <!-- business-user-profile-activities-filter -->

    <div v-for="activity in activities" :key="activity.id" class="activity-listing">
      <button class="booking-cards-title-box active flex items-center justify-between flex-wrap">
        <span class="booking-cards-date">{{
          activity.startDate.split(',')[0]
        }},&nbsp;{{ activity.startDate.split(',')[1] }}</span>
        <span class="booking-cards-found-results">{{ activity.list.length }} Results</span>
      </button>
      <booking-card :company_slug="company_slug" :lists="activity.list"
                    @onChangeActivityStatus="changeFacilityActivityStatus"
      />
    </div>
  </div>
</template>

<script>
import BookingCard from "./BookingCard";

export default {
  name: "BusinessProfileActivities",
  components: {BookingCard},
  props: {
    activities: {
      type: Array,
      required: true
    },
    company_slug: {
      type: String,
      required: true
    }
  },
  methods: {
    changeFacilityActivityStatus(data) {
      this.$emit("updateActivityStatus", data);
    }
  }
};
</script>

<style scoped>

</style>