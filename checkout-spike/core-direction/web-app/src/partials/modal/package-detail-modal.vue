<template>
  <div class="wrapper">
    <div id="tooltip-descriptive-hover" class="custom-modal m-auto overflow-y-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeModal">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <div class="activity-detail-card">
                  <div class="activity-detail-header flex items-center">
                    <div class="brand-img rounded-full">
                      <img :src="logoUrl" alt="" class="rounded-full">
                    </div>
                    <div class="brand-info-box">
                      <p class="brand-label">
                        Provider Name
                      </p>
                      <p class="brand-name">
                        {{ packageInformation.company_title ?? "CORE DIRECTION" }}
                      </p>
                    </div>
                  </div>
                  <div class="activity-detail-body">
                    <div class="package-short-info-box grid grid-cols-3 gap-2">
                      <div class="package-name col-span-2">
                        Membership Name
                      </div>
                      <div class="">
                        <p class="package-price">
                          {{ packageInformation.price ?? "36.75" }} AED
                        </p>
                        <p class="package-label">
                          Bought for
                        </p>
                      </div>
                    </div>
                    <div class="package-info-box">
                      <p class="package-info-label">
                        Type
                      </p>
                      <p class="package-info-title">
                        Membership
                      </p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div class="package-info-box">
                        <p class="package-info-label">
                          Payment Type
                        </p>
                        <p class="package-info-title">
                          Recurring Purchase
                        </p>
                      </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div class="package-info-box">
                        <p class="package-info-label">
                          No. of Sessions
                        </p>
                        <p class="package-info-title">
                          {{ packageInformation.visits ?? "Unlimited" }}
                        </p>
                      </div>
                      <div class="package-info-box">
                        <p class="package-info-label">
                          Used No. of Sessions
                        </p>
                        <p class="package-info-title">
                          {{ packageInformation.consumed ?? "N/A" }}
                        </p>
                      </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                      <div class="package-info-box">
                        <p class="package-info-label">
                          Validity
                        </p>
                        <p class="package-info-title">
                          {{ getDaysDifference }} Days
                        </p>
                      </div>
                    </div>
                    <div class="package-info-box">
                      <p class="package-t-c">
                        Package Terms &amp; Conditions
                      </p>
                      <p class="package-t-c--item" v-html="packageInformation.description ?? ''" />
                    </div>
                  </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <button :disabled="packageInformation.status === 'cancelling'"
                          class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize"
                          @click="cancelModal"
                  >
                    Cancel
                  </button>
                  <template v-if="packageType !== 'subscription'">
                    <router-link :to="bookingLink">
                      <button class="booking-checkin-mode-btn-proceed btn-modal-close rounded-full capitalize"
                              type="button"
                      >
                        Use
                      </button>
                    </router-link>
                  </template>
                  <template v-else>
                    <router-link :to="listingLink">
                      <button class="booking-checkin-mode-btn-proceed btn-modal-close rounded-full capitalize"
                              type="button"
                      >
                        Use
                      </button>
                    </router-link>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CloseIcon from "@/svgs/close-icon";

export default {
  name: "PackageDetailModal",
  components: {CloseIcon},
  props: {
    packageInformation: {
      type: Object,
    },
    packageType: {
      type: String,
      default: "member"
    }
  },
  methods: {
    closeModal() {
      this.$emit("closeModal", "closed");
    },
    formatCoordinate(coordinate) {
      return coordinate === "NAN" ? "" : coordinate;
    },
    isPackageTypeMember() {
      return this.packageType === "member";
    },
    cancelModal() {
      if (this.isPackageTypeMember()) {
        this.$emit("closeModal", "closed");
        return;
      }
      this.$emit("cancelSubscription");

    }


  },
  computed: {
    getDaysDifference() {
      if (this.isPackageTypeMember()) {
        return this.packageInformation.validity_days;
      }
      const start = new Date(this.packageInformation.start_date);
      const end = new Date(this.packageInformation.end_date);
      const differenceInTime = end.getTime() - start.getTime();
      const differenceInDays = differenceInTime / (1000 * 3600 * 24);
      return Math.ceil(differenceInDays);
    },
    logoUrl() {
      return this.packageInformation.hasOwnProperty("company_logo") ? this.constants.getImageUrl(`member/${this.packageInformation.company_logo}`) : require("@/assets/images/subscription.webp");
    },
    listingLink() {
      return {
        name: "Listing"
      };
    },
    bookingLink() {
      return {
        name: "Booking",
        params: {id: this.packageInformation.company_id},
        query: {
          lat: this.formatCoordinate(this.packageInformation.latitude),
          lng: this.formatCoordinate(this.packageInformation.longitude)
        }
      };
    }
  }

};
</script>

<style scoped>

</style>