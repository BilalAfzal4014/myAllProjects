<template>
  <div class="progress-bar">
    <div v-for="tab in tabs" :key="tab.id" :class="[tab.className, { active: activeTab === tab.id }]">
      <div class="progress-status" />
    </div>
  </div>
</template>

<script>
export default {
  name: "TabBars",
  props: {
    activeTab: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      tabs: [
        {
          id: "package",
          className: "package-membership-tab progress-status-box",
        },
        {
          id: "history",
          className: "booking-history-tab progress-status-box",
        },
        {
          id: "offers",
          className: "booking-redeemed-offers-tab progress-status-box",
        },
        {
          id: "details",
          className: "payment-details-tab progress-status-box",
        }
      ]
    };
  }
};
</script>

<style scoped>

</style>