<template>
  <div class="booking-card">
    <div class="booking-card-header flex items-start">
      <BusinessLogo />
      <h3 class="business-name text-left">
        CORE DIRECTION
      </h3>
    </div>
    <div class="booking-card-body">
      <ActivityInfo
        description="Core Premium Subscription"
        name="Core Premium Subscription"
      />
      <package-info :endDate="subscription.end_date"
                    :status="subscription.status"
                    cardType="subscription"
      />
    </div>
    <ActionButton :pack="subscription" cardType="subscription" @showPackageDetailModal="showDetailModal" />
  </div>
</template>

<script>

import BusinessLogo from "@/wallet/membership/BusinessLogo";
import ActivityInfo from "@/wallet/membership/ActivityInfo";
import PackageInfo from "@/wallet/membership/PackageInfo";
import ActionButton from "@/wallet/membership/ActionButtons";

export default {
  name: "SubscriptionCard",
  props: {
    subscription: {
      type: Object,
      required: true
    }
  },
  components: {ActionButton, PackageInfo, ActivityInfo, BusinessLogo},
  methods: {
    showDetailModal(subscription) {
      this.$emit("showDetailModal", subscription);
    }
  }

};
</script>

<style scoped>

</style>