<template>
  <div id="checkin-modal">
    <div class="form-container mx-auto">
      <div class="booking-cancelation-message-box">
        <div class="booking-cancelation-message-icon-box">
          <success-tick-sm-icon />
        </div>
        <p class="booking-cancelation-success-title mx-auto text-center">
          You’ve left group successfully
        </p>
      </div>

      <button class="booking-cancelation-success btn-modal-close rounded-full capitalize" @click="navigateToDashboard">
        Go to Dashboard
      </button>
    </div>
  </div>
</template>

<script>

import SuccessTickSmIcon from "@/svgs/success-tick-sm-icon";

export default {
  name: "GroupSuccessCancellationModal",
  components: {SuccessTickSmIcon},
  methods: {
    navigateToDashboard() {
      const {companySlug, $router} = this;
      const path = companySlug ? `/booking/${companySlug}` : "/listing";
      $router.push(path);
    }
  },
  props: {
    companySlug: {
      type: String,
      default: "",
    }
  }

};
</script>

<style scoped src="../assets/group-leave-modal.css">

</style>