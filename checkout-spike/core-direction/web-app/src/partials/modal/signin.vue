<template>
  <div id="signin-modal" class="custom-modal m-auto hidden">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="closeModal()">
              <close-icon />
            </div>
          </div>
          <div class="modal-body px-5">
            <div class="form-container mx-auto pb-20">
              <h3 class="modal-title text-center mt-10">
                Sign in
              </h3>
              <p class="modal-desc text-center my-10">
                Sign in to your account
              </p>
              <div>
                <div class="email-field-box mb-8">
                  <div class="group">
                    <label :class="{'active': email.length}" for="signInEmail">Username</label>
                    <input
                      id="signInEmail"
                      v-model="email"
                      class="cool border-b-1 border-black"
                      type="text"
                    >
                    <user-input-icon />
                  </div>
                </div>
                <div class="password-field-box mb-3">
                  <div class="group">
                    <label :class="{'active':password.length}" for="signInPassword">Password</label>
                    <input
                      id="signInPassword"
                      v-model="password"
                      :type="type"
                      class="cool border-b-1 border-black"
                    >
                    <password-show
                      v-show="password.length && type === 'password'"
                      @showPassword="togglePasswordVisibility"
                    />
                    <password-hide
                      v-show="password.length && type === 'text'"
                      @showPassword="togglePasswordVisibility"
                    />
                    <password-icon />
                  </div>
                </div>
                <button
                  class="btn-submit bg-gradient rounded-full mb-5 mt-3"
                  type="button"
                  @click="logIn"
                >
                  Sign In
                </button>
              </div>
              <div
                class="account-btn-box flex items-center justify-between mb-8"
              >
                <a class="underline btn-signup" href="#" @click="navigateToSignupPage">
                  Create New Account
                </a>
                <a class="underline" href="#" @click="showForgetPasswordModal">Forget Password?</a>
              </div>
              <SocialLogin />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import emitter from "tiny-emitter/instance";
import CloseIcon from "../../svgs/close-icon";
import UserInputIcon from "../../svgs/user-input-icon";
import * as toastr from "toastr";
import SocialLogin from "@/partials/SocialLogin";
import PasswordIcon from "../../svgs/users/password-icon";
import PasswordShow from "../../svgs/password/PasswordShow";
import PasswordHide from "../../svgs/password/PasswordHide";
import Joi from "joi";
import loginMixin from "@/mixin/loginMixin";

export default {
    name: "Signin",
    components: {
        PasswordHide,
        PasswordShow,
        PasswordIcon,
        SocialLogin,
        UserInputIcon,
        CloseIcon,
    },
    data: function () {
        return {
            email: "",
            password: "",
            type: "password",
        };
    },
    mixins: [loginMixin],
    methods: {
        showForgetPasswordModal: function () {
            this.closeModal();
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            emitter.emit("forget_password_modal", "show forget password up modal");
        },
        navigateToSignupPage() {
            if (this.$route.name !== "SignupComponent") {
                this.$router.push("/signup");
            } else {
                this.closeModal();
            }
        },
        closeModal() {
            const body = document.querySelector("body");
            const sign_in_modal = document.querySelector("#signin-modal");
            sign_in_modal.classList.add("hidden");
            body.classList.remove("overflow-y-hidden");
        },
        validateSchema() {
            let JoiPayLoad = {
                Email: this.email,
                Password: this.password,
            };
            const schema = Joi.object({
                Email: Joi.string().required(),
                Password: Joi.string().min(8).required(),
            });
            const {error} = schema.validate(JoiPayLoad);
            if (error) {
                toastr.error(error);
                return false;
            }
            return true;
        },

        logIn() {
            if (!this.validateSchema()) {
                return;
            }
            this.signin(this.email, this.password, true, true);
        },

        togglePasswordVisibility() {
            this.type = this.type === "text" ? "password" : "text";
        },
    },
    created() {
        emitter.on("sign_in_modal", () => {
            const sign_in_modal = document.querySelector("#signin-modal");
            sign_in_modal.classList.remove("hidden");
        });
    },
};
</script>

<style scoped>
#signin-modal{
  z-index: 1000;
}
.bg-gradient {
  background: #690fad !important;
}
</style>