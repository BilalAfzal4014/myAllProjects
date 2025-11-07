<template>
  <div class="wrapper">
    <div id="reset-password-modal" class="custom-modal m-auto">
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
                <h3 class="modal-title text-center">
                  New Password
                </h3>
                <p class="forget-password-modal-desc text-center">
                  Setup your new password
                </p>
                <div class="password-field-box mb-4">
                  <div class="password-field-box mb-7">
                    <div class="field-control-box">
                      <label class="form-label block" for="signInPassword">Enter new password</label>
                      <input id="signInPassword" class="form-control block cool border-b-1 border-black"
                             placeholder="johndoe@example.com"
                             type="email"
                      >
                      <password-reset-icon />
                    </div>
                    <p class="error-message flex mt-2">
                      <red-information-icon />
                      at least 8 characters. Containing 1 alpha numeric
                      and 1 upper case letter
                    </p>
                  </div>
                  <div class="password-field-box mb-14">
                    <div class="field-control-box">
                      <label class="form-label block" for="signInPassword">Re-enter new password</label>
                      <input id="signInPassword" class="form-control block cool border-b-1 border-black"
                             placeholder="johndoe@example.com"
                             type="email"
                      >
                      <password-reset-icon />
                    </div>
                  </div>
                </div>
                <button class="btn-submit bg-gradient rounded-full btn-confirm-password">
                  Confirm Password
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
import PasswordResetIcon from "@/svgs/password-reset/password-reset-icon";
import RedInformationIcon from "@/svgs/password-reset/red-information-icon";

export default {
    name: "SetNewPassword",
    components: {RedInformationIcon, PasswordResetIcon, CloseIcon}
};
</script>

<style scoped>
@import "../assets/reset-password.css";
</style>