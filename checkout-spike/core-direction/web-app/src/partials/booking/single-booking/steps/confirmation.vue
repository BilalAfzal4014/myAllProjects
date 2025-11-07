<template>
  <div class="cart-body mx-auto">
    <SocialSharingOnSignup :show-modal="isShownSocialSharingModal"
                           @setShowActivityModal="isShownSocialSharingModal = false"
    />
    <div class="purchase-confirmation">
      <div class="success-icon-box">
        <success-tick-icon />
      </div>
      <p class="success-message-title mx-auto">
        {{ confirmation.header }}
      </p>
      <button v-if="$route.name === 'purchase-package'" class="btn-calendar mx-auto" @click="$router.push('/')">
        Go to homepage
      </button>
      <div v-else>
        <p class="success-message-desc mx-auto">
          {{ confirmation.description }}
        </p>
        <button class="btn-share mx-auto" @click="shareActivity">
          Share your Activity
        </button>
        <button class="btn-calendar mx-auto" @click="$router.push('/calendar')">
          See Calendar
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import SuccessTickIcon from "@/svgs/success-tick-icon";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import emitter from "tiny-emitter/instance";

export default {
  components: {SocialSharingOnSignup, SuccessTickIcon},
  props: {
    confirmation: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      isShownSocialSharingModal: false,
    };
  },
  methods: {
    shareActivity() {
      this.isShownSocialSharingModal = true;
      emitter.emit(
        "social_sharing_modal",
        "paidBooking"
      );
    }
  }


};
</script>
