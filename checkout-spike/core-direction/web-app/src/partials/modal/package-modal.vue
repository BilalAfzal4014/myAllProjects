<template>
  <div id="tooltip-descriptive-hover" class="custom-modal m-auto overflow-y-auto">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="$emit('closeModal','closed')">
              <svg fill="none" height="36" viewBox="0 0 36 36" width="36" xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M30.7336 5.26636C23.7103 -1.75545 12.2885 -1.75545 5.2652 5.26636C-1.75507 12.2882 -1.75507 23.713 5.2652 30.7348C8.77685 34.245 13.3885 35.9992 18.0002 35.9992C22.6119 35.9992 27.222 34.2449 30.7336 30.7348C37.7555 23.7131 37.7555 12.2882 30.7336 5.26636ZM25.4271 23.3063C26.0139 23.8931 26.0139 24.8415 25.4271 25.4283C25.1345 25.7209 24.7503 25.868 24.3661 25.868C23.982 25.868 23.5977 25.7209 23.3051 25.4283L18.0001 20.1218L12.6966 25.4267C12.4025 25.7194 12.0183 25.8665 11.6356 25.8665C11.2515 25.8665 10.8672 25.7194 10.5746 25.4267C9.98785 24.84 9.98785 23.89 10.5746 23.3048L15.8781 17.9998L10.5731 12.6948C9.98637 12.1081 9.98637 11.1581 10.5731 10.5729C11.1584 9.98612 12.1083 9.98612 12.6951 10.5729L18.0001 15.8778L23.305 10.5729C23.8918 9.98612 24.8402 9.98612 25.427 10.5729C26.0137 11.1581 26.0137 12.1081 25.427 12.6948L20.122 17.9998L25.4271 23.3063Z"
                  fill="#C4C4C4"
                />
              </svg>
            </div>
          </div>
          <div class="modal-body px-5">
            <div class="form-container mx-auto">
              <!-- <form action=""> -->
              <div class="activity-detail-card">
                <div class="activity-detail-header flex items-center">
                  <div class="brand-img rounded-full">
                    <!-- eslint-disable-next-line vue/this-in-template -->
                    <img :src="this.constants.getImageUrl('member/'+pack.company_logo)" alt="" class="rounded-full">
                  </div>
                  <div class="brand-info-box">
                    <p class="brand-label">
                      Provider Name
                    </p>
                    <p class="brand-name">
                      {{ pack.company_title }}
                    </p>
                  </div>
                </div>
                <div class="activity-detail-body">
                  <div class="grid grid-cols-3 gap-2">
                    <div class="package-name col-span-2">
                      {{ pack.name }}
                    </div>
                    <div class="package-price">
                      {{ pack.price }} AED
                    </div>
                  </div>
                  <div class="package-info-box">
                    <p class="package-info-label">
                      Type
                    </p>
                    <p class="package-info-title">
                      Package
                    </p>
                  </div>
                  <div class="package-info-box">
                    <p class="package-info-label">
                      Payment Type
                    </p>
                    <p class="package-info-title">
                      One time purchase
                    </p>
                  </div>
                  <div class="package-info-box">
                    <p class="package-info-label">
                      Validity
                    </p>
                    <p class="package-info-title">
                      {{ pack.validity_days }} Days
                    </p>
                  </div>
                  <div class="package-info-box">
                    <p class="package-t-c">
                      Package Terms &amp; Conditions
                    </p>
                    <p class="package-t-c--item" v-html="pack.description" />
                  </div>
                </div>
              </div>

              <button class="booking-checkin-mode-btn-proceed btn-modal-close rounded-full capitalize"
                      @click="purchasePackage(pack)"
              >
                Buy
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Emitter from "tiny-emitter/instance";

export default {
    name: "PackageModal",
    props: {
        pack: {
            type: Object,
            required: true,
        },
    },
    methods: {
        purchasePackage(pack) {
            if (!!this.$store.getters.getStoreTokenGetters) {
                this.$router.push("/purchase-package/" + pack.package_id);
                return false;
            }
            this.$emit("hidePackageModal");
            Emitter.emit("sign_in_modal", "show sign in modal");
        }

    }
};
</script>

<style scoped>

</style>