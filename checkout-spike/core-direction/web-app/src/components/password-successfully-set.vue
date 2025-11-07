<template>
  <div class="wrapper">
    <div id="password-reset-confirmation-modal" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <div class="password-reset-confirmed-success-icon mt-8">
                  <success-tick-icon />
                </div>
                <h3 class="modal-title text-center">
                  You’re all set
                </h3>
                <p class="reset-password-modal-desc">
                  We have re-set your new password
                </p>
                <button class="btn-submit bg-gradient rounded-full btn-confirm-password">
                  Log in
                </button>
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
import SuccessTickIcon from "@/svgs/success-tick-icon";

export default {
    name: "PasswordSuccessfullySet",
    components: {SuccessTickIcon, CloseIcon}
};
</script>

<style scoped>

</style>