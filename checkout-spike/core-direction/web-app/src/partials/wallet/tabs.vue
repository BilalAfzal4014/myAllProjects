<template>
  <div class="wallet-header">
    <ul class="flex items-center justify-between">
      <li v-for="tab in tabs" :key="tab.id">
        <button
          :class="[tab.className, { active: activeTab === tab.id }]"
          type="button"
          @click="switchTab($event, tab.id)"
        >
          <span class="icon-box">
            <component :is="tab.icon" />
          </span>
          <span class="content-box">{{ tab.label }}</span>
        </button>
      </li>
    </ul>
    <tab-bars :active-tab="activeTab" />
  </div>
</template>

<script>
import PackageMembershipIcon from "../../svgs/wallet/package-membership-icon";
import BookingHistory from "../booking/BookingHistory";
import PaymentDetailsIcon from "../../svgs/wallet/payment-details-icon";
import RedeemedOffers from "../../svgs/wallet/redeemed-offers";
import TabBars from "./tab-bars";

export default {
  name: "Tabs",
  components: {TabBars, PaymentDetailsIcon, BookingHistory, PackageMembershipIcon, RedeemedOffers},
  props: {
    activeTab: {
      type: String,
      required: true
    },
  },
  data() {
    return {
      tabs: [
        {
          id: "package",
          className: "package-membership-tab-btn",
          icon: "package-membership-icon",
          label: "Packages & Membership"
        },
        {
          id: "history",
          className: "booking-history-tab-btn",
          icon: "booking-history",
          label: "Booking History"
        },
        {
          id: "offers",
          className: "booking-redeemed-offers-tab-btn",
          icon: "redeemed-offers",
          label: "Redeemed Offers"
        },
        {
          id: "details",
          className: "payment-details-tab-btn",
          icon: "payment-details-icon",
          label: "Payment Details"
        }
      ]
    };
  },
  methods: {
    switchTab(event, argument) {
      this.$emit("clicked", argument);
    },
  },
};
</script>

<style scoped>

</style>