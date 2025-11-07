<template>
  <div class="cart-body mx-auto">
    <div class="select-package-box">
      <usePackage
        :key="preReqsCounterForUsePackage"
        :activity-id="$route.params.activityId"
        v-bind="{selectPackage: chooseExistingPackage}"
      />
      <redeemPackage
        @fetchPreReqsAgain="fetchPreReqsAgain"
      />
      <purchasePackage
        :key="preReqsCounterForPurchasePackage"
        :activity-id="$route.params.activityId"
        v-bind="{selectPackage: chooseNewPackage}"
      />
    </div>
  </div>
</template>

<script>
import usePackage from "./partials/use-package";
import redeemPackage from "./partials/redeem-package";
import purchasePackage from "./partials/purchase-package";

export default {
    components: {
        redeemPackage,
        usePackage,
        purchasePackage
    },
    data() {
        return {
            selectedPackage: null,
            preReqsCounterForUsePackage: 0,
            preReqsCounterForPurchasePackage: 1,
        };
    },
    mounted() {
    },
    methods: {
        chooseNewPackage(newPackage) {
            this.choosePackage(newPackage);
        },
        chooseExistingPackage(existingPackage) {
            this.choosePackage(existingPackage);
        },
        choosePackage(Package) {
            this.selectedPackage = Package;
            this.$emit("moveNextTab", this.selectedPackage);
        },
        fetchPreReqsAgain() {
            this.preReqsCounterForPurchasePackage += 2;
            this.preReqsCounterForUsePackage++;
        }
    }
};
</script>

<style>

</style>