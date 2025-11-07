<template>
  <div class="form-container mx-auto">
    <div class="new-package-box">
      <p class="select-package-title">
        Buy a new package to continue
      </p>
      <div class="package-card-box select-package-section">
        <packageCard
          v-for="assignedPackage in assignedPackages"
          :key="assignedPackage.package_id"
          :package-details="{...assignedPackage, purchased: false}"
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
import PackageModelBody from "./package-modal-body";
import * as toastr from "toastr";

export default {
    components: {
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
            assignedPackages: [],
        };
    },
    mounted() {
        this.fetchPreReqData();
    },
    methods: {
        fetchPreReqData() {
            this.fetchPackagesAssignedToTheActivity();
        },
        fetchPackagesAssignedToTheActivity() {
            this.oldApi("get",
                this.constants.getUrl("getAssignedPackageToActivity") + "/" + this.activityId
            ).then((response) => {
                this.assignedPackages = response.data;
            }).catch((error) => {
                toastr.error(error.message);
            });
        }
    }
};
</script>

<style scoped>
</style>