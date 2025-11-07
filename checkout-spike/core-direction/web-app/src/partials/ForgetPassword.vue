<template>
  <div id="forget_password" class="wrapper hidden">
    <div id="account-recovery-modal" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeModal()">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <h3 class="modal-title text-center mt-8">
                  Account Recovery
                </h3>
                <p class="forget-password-modal-desc text-center">
                  Enter your username or email
                </p>
                <div class="email-field-box mb-8">
                  <div class="forget-password-group">
                    <label for="signInEmail">Username/Email</label>
                    <input id="signInEmail" v-model="username" class="cool border-b-1 border-black" type="text">
                    <UserIcon />
                  </div>
                </div>
                <button class="btn-submit bg-gradient rounded-full mb-8" @click="sendForgetRequest">
                  Continue
                </button>
                <button class="btn btn-signIn-instead block mb-16 mx-auto" @click="showSignInModal">
                  Sign in instead
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
import emitter from "tiny-emitter/instance";
import CloseIcon from "@/svgs/close-icon";
import UserIcon from "@/svgs/user-icon";
import toastr from "toastr";
import {forgotPassword} from "@/apiManager/user";

export default {
    name: "ForgetPassword",
    components: {UserIcon, CloseIcon},
    created() {
        emitter.on("forget_password_modal", () => {
            const forget_password = document.querySelector("#forget_password");
            forget_password.classList.remove("hidden");
        });
    },
    methods: {
        closeModal() {
            const body = document.querySelector("body");
            const forget_password = document.querySelector("#forget_password");
            forget_password.classList.add("hidden");
            body.classList.remove("overflow-y-hidden");
        },
        showSignInModal: function () {
            this.closeModal();
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            emitter.emit("sign_in_modal", "show sign in modal");
        },
        sendForgetRequest() {
            if (this.username === "") {
                toastr.error("Username is required");
                return false;
            }

            forgotPassword({
                username: this.username
            }).then((response) => {
                if (response.statusCode === 200) {
                    toastr.success(response.data.status);
                    const body = document.querySelector("body");
                    this.closeModal();
                    body.classList.add("overflow-y-hidden");
                    emitter.emit("recover_password", {
                        username: this.username
                    });
                    this.username = "";
                } else {
                    toastr.error(response.data.status);
                }
            }).catch((error) => {
                toastr.error(error);
            });
        },

    },
    data() {
        return {
            username: "",
        };
    }

};
</script>

<style scoped>

</style>