<template>
  <div>
    <signin />
    <SocialSharingOnSignup v-if="socialSharingModal" />
    <ForgetPassword v-if="forgetPasswordModal" />
    <RecoverPassword v-if="recoverPasswordModal" />
    <header-component @showed="showedAllModal" />
  </div>
</template>

<script>
import HeaderComponent from "./header";
import Signin from "./modal/signin";
import ForgetPassword from "@/partials/ForgetPassword";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import RecoverPassword from "@/partials/RecoverPassword";
import Emitter from "tiny-emitter/instance";

export default {
    components: {
        RecoverPassword,
        SocialSharingOnSignup,
        ForgetPassword,
        Signin,
        HeaderComponent
    },
    data() {
        return {
            signInModal: false,
            signUpModal: false,
            forgetPasswordModal: false,
            recoverPasswordModal: false,
            socialSharingModal: false,
        };
    },
    mounted() {
        if (this.$route.query.redirect) {
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            Emitter.emit("sign_in_modal", "show sign in modal");
        }
    },

    methods: {
        showedAllModal() {
            this.signInModal = true;
            this.signUpModal = true;
            this.socialSharingModal = true;
            this.recoverPasswordModal = true,
            this.forgetPasswordModal = true;
        }
    }
};
</script>