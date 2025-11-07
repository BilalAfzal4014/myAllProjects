<template>
  <div class="wallet-body">
    <div class="package-membership-content-box">
      <div class="wallet-table-header">
        <p class="wallet-selected-tab-heading">
          Packages & Membership
        </p>
      </div>
      <package-search :membership-packages="membershipPackages" @reset="resetFilter" @search="searchFilter" />
      <membership-card-listings :membership-packages="membershipPackages" />
      <subscription-listing :subscriptions="subscriptions" class="mt-4" @cancelledSubscription="cancelSubscription" />
      <h3 v-if="showNoDataMessage" class="text-center">
        No data found.
      </h3>
    </div>
  </div>
</template>

<script>
import PackageSearch from "./PackageSearch";
import MembershipCardListings from "./MembershipCardListings";
import * as toastr from "toastr";
import {getSubscriptionList} from "@/apiManager/core-premium";
import SubscriptionListing from "@/components/member-subscription/SubscriptionListing";

const DEFAULT_PAYLOAD = {
  company_ids: [],
  status: [],
  date: "",
};
export default {
  name: "PackageMembershipComponent",
  components: {SubscriptionListing, MembershipCardListings, PackageSearch},
  data() {
    return {
      membershipPackages: [],
      subscriptions: [],
    };
  },
  created() {
    this.getPackageMembership(this.getPackagesPayload({}));
    this.getSubscription();
  },
  computed: {
    showNoDataMessage() {
      return this.membershipPackages.length === 0 && this.subscriptions.length === 0;
    }
  },
  methods: {
    cancelSubscription(subscriptionId) {
      const thirtyDaysFromNow = new Date();
      thirtyDaysFromNow.setDate(thirtyDaysFromNow.getDate() + 30);
      const index = this.subscriptions.findIndex(subscription => subscription.subscription_id === subscriptionId);
      if (index !== -1) {
        this.$set(this.subscriptions, index, {
          ...this.subscriptions[index],
          status: "cancelling",
          end_date: this.formatDate(thirtyDaysFromNow),
        });
      }
    },
    formatDate(date) {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      const hours = String(date.getHours()).padStart(2, "0");
      const minutes = String(date.getMinutes()).padStart(2, "0");
      const seconds = String(date.getSeconds()).padStart(2, "0");
      const milliseconds = String(date.getMilliseconds()).padStart(3, "0");
      const time = `${hours}:${minutes}:${seconds}.${milliseconds}`;
      return `${year}-${month}-${day}T${time}Z`;
    },
    async getSubscription() {
      try {
        const response = await getSubscriptionList();
        this.subscriptions = response.data.membership_subscriptions;
      } catch (error) {
        const errorMessage = error?.response?.data?.errors?.[0]?.error || "An unexpected error occurred";
        toastr.error(errorMessage);
      }
    },
    resetFilter() {
      this.getPackageMembership(this.getPackagesPayload({}));
    },
    getPackagesPayload(payload) {
      if (!payload) {
        return DEFAULT_PAYLOAD;
      }
      const {providers = [], status = []} = payload;
      return {
        company_ids: providers,
        status,
        date: "",
      };
    },
    searchFilter(payload) {
      this.getPackageMembership(this.getPackagesPayload(payload));
    },
    getPackageMembership(payload) {
      this.oldApi("post",
        this.constants.getUrl("memberPackage"),
        payload, true
      ).then((response) => {
        this.membershipPackages = response.data.sort((firstObject, secondObject) => secondObject.member_package_id - firstObject.member_package_id);

      }).catch((error) => {
        toastr.error(error[0].response.data.message);
      });
    },

  }
};
</script>

<style scoped>

</style>