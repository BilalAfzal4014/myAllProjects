<template>
  <div class="business-profile-packages-box">
    <package-modal v-if="isShownModal" :pack="packInformation" @closeModal="closeModal"
                   @hidePackageModal="isShownModal = false"
    />
    <div v-for="pack in packages" :key="pack.package_id" class="package-card-box">
      <div class="package-card">
        <package-header :pack="pack" />
        <button class="btn-package-detail flex items-center" @click="showPackageDetailModal(pack)">
          <info-icon />
          View Package Details
        </button>
        <package-footer :pack="pack" />
      </div>
    </div>
  </div>
</template>

<script>
import PackageHeader from "./package-header";
import InfoIcon from "../../../svgs/company/info-icon";
import PackageFooter from "./package-footer";
import PackageModal from "../../modal/package-modal";

export default {
    name: "PackageCard",
    components: {PackageModal, PackageFooter, InfoIcon, PackageHeader},
    props: {
        packages: {
            type: Array,
            required: true,
            default: (() => [])
        }

    },
    data() {
        return {
            isShownModal: false,
            packInformation: {}
        };
    },
    methods: {
        closeModal() {
            this.isShownModal = false;
        },
        showPackageDetailModal(pack) {
            this.packInformation = {};
            this.packInformation = pack;
            this.isShownModal = true;
        }
    }

};
</script>

<style scoped>
@media screen and (max-width: 767px) {
  #business-profile .business-profile-packages-box {
    margin-top: 50px !important;
  }
}
</style>