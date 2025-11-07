<template>
  <div>
    <SubscriptionInfo :packageData="packageData" />
    <SubscriptionFeaturesAllCards />
    <SubscriptionPartners />
    <SubscriptionFAQs />
    <SubscriptionCard v-if="!$store.state?.userProfile?.isPremiumUser" />
  </div>
</template>

<script>
import {getPackageDetail} from "@/apiManager/core-premium.js";
import {
  SubscriptionCard,
  SubscriptionFAQs,
  SubscriptionFeaturesAllCards,
  SubscriptionInfo,
  SubscriptionPartners
} from "@/components/CorePremiumSubscription";

export default {
  name: "CorePremium",
  data() {
    return {
      packageData: {},
    };
  },
  components: {
    SubscriptionInfo,
    SubscriptionFeaturesAllCards,
    SubscriptionPartners,
    SubscriptionFAQs,
    SubscriptionCard,
  },
  methods: {
    async packageDetail() {
      try {
        const res = await getPackageDetail();
        if (res?.statusCode === 200 && res?.data) {
          this.packageData = res.data;
        }
      } catch (e) {
        return e.message;
      }
    }
  },
  beforeMount() {
    this.$store.commit("setCorePremiumModal", false);
    this.packageDetail();
  }
};
</script>

<style>

</style>