<template>
  <div>
    <package-detail-modal v-if="isShownModal" :package-information="packageInformation"
                          @closeModal="closeModal"
    />
    <div class="booking-card-box">
      <div v-for="(pack,index) in membershipPackage" :key="index" class="booking-card">
        <div class="booking-card-header flex items-start">
          <BusinessLogo :logo="pack.company_logo" />
          <BusinessLink :id="pack.company_id" :latitude="pack.latitude"
                        :longitude="pack.longitude"
                        :title="pack.company_title"
          />
        </div>
        <div class="booking-card-body">
          <ActivityInfo
            :description="pack.description"
            :name="pack.name"
          />
          <package-info :consumed="pack.consumed" :package_name="pack.package_name" :status="pack.status"
                        :visits="pack.visits"
          />
        </div>
        <ActionButtons :company_id="pack.company_id" :latitude="pack.latitude" :longitude="pack.longitude"
                       :pack="pack" @showPackageDetailModal="showPackageDetailModal"
        />
      </div>
    </div>
  </div>
</template>

<script>
import PackageDetailModal from "../../partials/modal/package-detail-modal";
import BusinessLogo from "./BusinessLogo.vue";
import BusinessLink from "./BusinessLink.vue";
import PackageInfo from "./PackageInfo.vue";
import ActivityInfo from "./ActivityInfo.vue";
import ActionButtons from "./ActionButtons.vue";

export default {
  name: "MembershipCard",
  components: {
    PackageDetailModal, BusinessLogo,
    BusinessLink,
    PackageInfo,
    ActivityInfo,
    ActionButtons,
  },
  props: {
    membershipPackage: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      isShownModal: false,
      packageInformation: {},

    };
  },
  methods: {
    closeModal() {
      this.isShownModal = false;
    },
    cancelSubscription() {
      this.isShowCancelSubscriptionModal = true;
    },
    showPackageDetailModal(packageInfo) {
      this.packageInformation = {};
      this.packageInformation = packageInfo;
      this.isShownModal = true;
    }
  }
};
</script>

