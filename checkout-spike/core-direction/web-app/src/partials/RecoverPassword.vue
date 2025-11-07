<template>
  <div id="recover_password" class="wrapper hidden">
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
                  Verification Code
                </p>
                <p class="forget-password-modal-desc text-center">
                  Hello<strong>  {{ username }} </strong>!
                </p>
                <div class="email-field-box mb-8">
                  <div class="forget-password-group">
                    <label for="signInEmail">Code</label>
                    <input id="signInEmail" v-model="code" class="cool border-b-1 border-black" type="text">
                    <UserIcon />
                  </div>
                </div>
                <div class="email-field-box mb-8">
                  <div class="forget-password-group">
                    <label for="signInEmail">New Password</label>
                    <input id="signInEmail" v-model="password" class="cool border-b-1 border-black" type="password">
                    <UserIcon />
                  </div>
                </div>
                <button class="btn-submit bg-gradient rounded-full mb-8" @click="recoverPasswordRequest">
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
import {forgotPassword, recoverPassword} from "@/apiManager/user";

export default {
    name: "RecoverPassword",
    components: {UserIcon, CloseIcon},
    created() {
        emitter.on("recover_password", (data) => {
            this.username = data.username;
            const recoverPassword = document.querySelector("#recover_password");
            recoverPassword.classList.remove("hidden");
        });
    },
    methods: {
        closeModal() {
            const body = document.querySelector("body");
            const recoverPassword = document.querySelector("#recover_password");
            recoverPassword.classList.add("hidden");
            body.classList.remove("overflow-y-hidden");
        },
        showSignInModal: function () {
            this.closeModal();
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            emitter.emit("sign_in_modal", "show sign in modal");
        },
        recoverPasswordRequest() {
            if (this.code === "") {
                toastr.error("Verification Code is required");
                return false;
            }
            if (this.username === "") {
                toastr.error("Username is required");
                return false;
            }
            if (this.password === "") {
                toastr.error("New Password is required");
                return false;
            }
            recoverPassword({
                username: this.username,
                verificationCode: this.code,
                newPassword: this.password,
            }).then((response) => {
                this.code = "";
                this.password = "";
                this.username = "";
                if(response.statusCode < 203){
                    toastr.success("Your new password has been created.");
                    this.showSignInModal();
                }else {
                    toastr.error("Something went wrong while creating the Password. Please try again later.");
                }
            }).catch((error) => {
                toastr.error(error.message);
            });
        },

    },
    data() {
        return {
            code: "",
            username: "",
            password: ""
        };
    }

};
</script>

<style scoped>

</style>