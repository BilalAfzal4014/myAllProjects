<template>
  <div class="success-message-box">
    <div class="success-icon-box">
      <success-icon />
    </div>
    <p class="success-title">
      THANK YOU
    </p>
    <router-link class="additional-info-link" to="/complete-profile-page">
      Let's create your profile!
      <arrow-icon />
    </router-link>
    <router-link class="btn-close" to="/community">
      No, I will do this later
    </router-link>
    <p class="success-desc">
      By completing your profile, you ca expect the ultimate user experience geared to
      inspiring you and helping you inspire others!!! Help us to help you inspire movement!!!
    </p>
  </div>
</template>

<script>
import SuccessIcon from "@/svgs/signup/success-icon";
import ArrowIcon from "@/svgs/signup/arrow-icon";

export default {
  name: "AdditionalInformationSignup",
  components: {ArrowIcon, SuccessIcon},
  props: {
    username: {
      type: String,
      required: true,
    },
    password: {
      type: String,
      required: true
    }
  }
};
</script>

<style scoped>


</style>