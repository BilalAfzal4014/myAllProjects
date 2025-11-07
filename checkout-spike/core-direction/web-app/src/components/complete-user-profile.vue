<template>
  <div class="wrapper">
    <Signin />
    <section id="additional-info">
      <HeaderSignup :is-account-created="true" :is-hide-home-page-link="false" />
      <div class="custom-container">
        <set-user-profile />
      </div>
      <footer-component />
    </section>
  </div>
</template>

<script>
import HeaderSignup from "@/components/signup/header";
import Signin from "@/partials/modal/signin";
import SetUserProfile from "@/components/signup/set-user-profile";
import FooterComponent from "@/partials/footer";
import {updateMetaInformation} from "@/utils";

export default {
  name: "CompleteUserProfile",
  components: {FooterComponent, SetUserProfile, Signin, HeaderSignup},
  mounted() {
    updateMetaInformation("Sign-up | Core Direction", "", "Sign-up to Core Direction and start #inspiringmovement", "Sign-up | Core Direction", "Sign-up to Core Direction and start #inspiringmovement", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/signup");
  },

};
</script>

<style scoped>

</style>