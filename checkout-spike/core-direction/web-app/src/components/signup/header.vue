<template>
  <div id="signup-header">
    <div class="custom-container flex items-center justify-between">
      <router-link to="/">
        <img alt="" class="brand-img" src="/assets/images/logo-Transparent.svg">
      </router-link>
      <ul class="nav-list flex items-center">
        <li v-if="isHideHomePageLink" class="nav-item mr-7">
          <router-link class="nav-link home-link" to="/">
            Home
          </router-link>
        </li>
        <li v-if="!isAccountCreated" class="nav-item">
          <button class="nav-link btn-signin" @click="showSignInModal">
            Sign In
          </button>
        </li>
        <li v-if="isAccountCreated" class="nav-item">
          <router-link class="nav-link signin-link" to="/">
            I will do this later
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
import Emitter from "tiny-emitter/instance";

export default {
    name: "HeaderSignup",
    props: {
        isAccountCreated: {
            type: Boolean,
            required: true,
        },
        isHideHomePageLink: {
            type: Boolean,
            required: true,
        }
    },
    methods: {
        showSignInModal: function () {
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            Emitter.emit("sign_in_modal", "show sign in modal");
        },
    }
};
</script>

<style scoped>

</style>