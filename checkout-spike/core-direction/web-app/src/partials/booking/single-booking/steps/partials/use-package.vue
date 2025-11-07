<template>
  <div :class="containerClasses">
    <div class="choose-package-box">
      <SocialSharingOnSignup />
      <p class="select-package-title">
        Choose from your already purchased packages
      </p>
      <div class="package-card-box select-package-section">
        <packageCard
          v-for="(purchasedPackage, index) in alreadyPurchasedPackages"
          :key="index"
          :package-details="{...purchasedPackage, purchased: true}"
          v-bind="$attrs"
          @sendPackageDescription="getPackageDescription"
        />
      </div>
      <generalModal
        :modal-heading="'Package'"
        :modal-is-open="modal.isModalOpen"
        :modal-title="'Package Details'"
        @closeModal="closeModal"
      >
        <template v-slot:modalBody>
          <PackageModelBody
            :package-details="modal.packageDetails"
            :package-provider-image="constants.getImageUrl('member/'+ modal.packageDetails.company_logo)"
          />
        </template>
      </generalModal>
    </div>
  </div>
</template>

<script>
import packageCard from "./package-card";
import packageModalMixins from "../mixins/package-modal-mixins";
import SocialSharingOnSignup from "../../../../SocialSharingOnSignup";
import PackageModelBody from "./package-modal-body";
import * as toastr from "toastr";

export default {
    components: {
        SocialSharingOnSignup,
        packageCard,
        PackageModelBody
    },
    props: {
        activityId: {
            type: String,
            required: true
        }
    },
    mixins: [packageModalMixins],
    data() {
        return {
            alreadyPurchasedPackages: []
        };
    },
    mounted() {
        this.fetchPreReqData();
    },
    methods: {
        fetchPreReqData() {
            this.fetchPackagesPurchasedAndAssignedToTheActivity();
        },
        async fetchPackagesPurchasedAndAssignedToTheActivity() {
            try {
                const url = `${this.constants.getUrl("getPurchasedPackageOfActivity")}/${this.activityId}`;
                const response = await this.oldApi("get", url);
                this.alreadyPurchasedPackages = response.data;
            } catch (error) {
                toastr.error(error.message);
            }
        }
    },
    computed: {
        containerClasses() {
            return [
                "mx-auto",
                this.alreadyPurchasedPackages.length ? "form-control" : ""
            ].join(" ").trim();
        }
    }
};

</script>
<style scoped>
@media screen and (min-width: 992px) {
  .form-container {
    padding-top: 116px;
    padding-bottom: 113px;
  }
}
</style>