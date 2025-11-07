<template>
  <div class="booking-card-box">
    <package-detail-modal v-if="isShownModal" :package-information="subscriptionDetail"
                          packageType="subscription"
                          @cancelSubscription="showCancelSubscriptionModal" @closeModal="closeModal"
    />
    <CancelSubscriptionModal v-if="isShowCancelSubscriptionModal" :subscriptionDetail="subscriptionDetail"
                             @cancelSubscription="confirmCancelSubscription"
                             @closeModal="isShowCancelSubscriptionModal = false"
    />
    <SuccessfullyCancelSubscriptionModal v-if="isSuccessfullyCancelSubscription"
                                         @closeCancelSubscriptionSuccessfullyModal="isSuccessfullyCancelSubscription = false"
    />
    <div v-for="(subscription,index) in subscriptions" :key="index">
      <subscription-card :subscription="subscription" @showDetailModal="showDetailModal" />
    </div>
  </div>
</template>

<script>
import SubscriptionCard from "@/components/cards/SubscriptionCard";
import PackageDetailModal from "@/partials/modal/package-detail-modal";
import CancelSubscriptionModal from "@/components/modals/CancelSubscriptionModal";
import SuccessfullyCancelSubscriptionModal from "@/components/modals/SuccessfullyCancelSubscriptionModal";
import {cancelUserSubscription} from "@/apiManager/core-premium";
import * as toastr from "toastr";

export default {
  name: "SubscriptionListingVue",
  props: {
    subscriptions: {
      type: Array,
      default: () => [],
    }
  },
  data() {
    return {
      isShownModal: false,
      subscriptionDetail: {},
      isShowCancelSubscriptionModal: false,
      isSuccessfullyCancelSubscription: false
    };
  },
  components: {SuccessfullyCancelSubscriptionModal, CancelSubscriptionModal, PackageDetailModal, SubscriptionCard},
  methods: {
    showCancelSubscriptionModal() {
      this.isShownModal = false;
      this.isShowCancelSubscriptionModal = true;
    },
    showDetailModal(subscription) {
      this.subscriptionDetail = {};
      this.subscriptionDetail = subscription;
      this.isShownModal = true;
    },
    closeModal() {
      this.subscriptionDetail = {};
      this.isShownModal = false;
    },
    async confirmCancelSubscription() {
      const {subscription_id} = this.subscriptionDetail;
      await this.cancelSubscription({
        "subscriptionId": subscription_id
      });
      this.isShowCancelSubscriptionModal = false;
      this.isSuccessfullyCancelSubscription = true;
      this.$emit("cancelledSubscription", subscription_id);
    },
    async cancelSubscription(payload) {
      try {
        await cancelUserSubscription(payload);
      } catch (error) {
        const errorMessage = error?.response?.data?.errors?.[0]?.error || "An unexpected error occurred";
        toastr.error(errorMessage);
      }
    },

  }
};
</script>

<style scoped>

</style>