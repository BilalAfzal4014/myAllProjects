<template>
  <div class="wrapper">
    <div id="CorePremium-modal" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="close">
                <close-icon />
              </div>
            </div>
            <div class="modal-body">
              <div class="offer-details-box">
                <p class="subtitle">
                  A world of fitness, health &amp; happiness benefits for only <strong>AED
                    36.75</strong> monthly.
                </p>
                <p class="title">
                  Offer Details
                </p>
                <ul class="tmc-list">
                  <li class="tmc-item">
                    Join Monthly Challenges, Get Active &amp; Win Prizes
                  </li>
                  <li class="tmc-item">
                    Track Steps, Heart Rate or Log Activity to earn points
                  </li>
                  <li class="tmc-item">
                    150 Monthly Core Points = Prize Draw Entry
                  </li>
                  <li class="tmc-item">
                    Monthly Prizes worth +AED3,000
                  </li>
                  <li class="tmc-item">
                    Access to Discount Partner Deals &amp; Offers
                  </li>
                  <li class="tmc-item">
                    Free and Discounted Access to Events
                  </li>
                </ul>
                <p class="title">
                  Terms &amp; Conditions
                </p>
                <p class="desc">
                  On registration your payment will auto-renew monthly at AED 36.75. You can cancel any
                  time during your&nbsp;subscription.
                </p>
              </div>
              <div class="redeem-details-box">
                <p class="subtitle">
                  Upgrade your account
                </p>
                <div class="title-box">
                  <p class="title">
                    <strong>Subscribe to</strong>
                  </p>
                  <div class="core-premium-icon-box">
                    <coredirection-icon />
                  </div>
                  <p class="title">
                    <strong>Core</strong>Premium
                  </p>
                </div>
                <div class="btn-box">
                  <router-link class="btn-upgrade-account" to="/corepremium">
                    Upgrade your account
                  </router-link>
                  <button class="btn-learn-more">
                    Learn more
                  </button>
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
import CoredirectionIcon from "@/svgs/coredirection-icon";

export default {
  name: "SubscribeCorePremiumModal",
  components: {CoredirectionIcon, CloseIcon},
  methods: {
    close() {
      this.$emit("close");
    }

  }

};
</script>

<style scoped>
@import "../../assets/css/subscribe-corepremium.css";
</style>