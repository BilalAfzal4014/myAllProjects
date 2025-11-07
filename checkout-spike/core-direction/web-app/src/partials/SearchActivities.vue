<template>
  <div>
    <div v-for="activity in activities" :key="activity.id" class="custom-container">
      <ActivityBookingDate :activity="activity" />
      <ActivityBookingLists :activity="activity" @onChangeActivityStatus="changeActivityStatus"
                            @updateActivities="getUpdatedActivities"
      />
    </div>
  </div>
</template>

<script>

import ActivityBookingDate from "../partials/activity/ActivityBookingDate";
import ActivityBookingLists from "../partials/activity/ActivityBookingLists";

export default {
  name: "SearchActivities",
  props: {
    activities: {
      type: Array,
      required: true
    },
  },
  components: {
    ActivityBookingLists,
    ActivityBookingDate
  },
  methods: {
    getUpdatedActivities() {
      this.$parent.getActivitiesList();
    },
    changeActivityStatus(activity) {
      this.$emit("changeActivityStatus", activity);
    }
  }
};
</script>

<style scoped>

</style>