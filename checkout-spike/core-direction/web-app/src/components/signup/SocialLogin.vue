<template>
  <ul class="social-list mx-auto">
    <li v-for="socialLink in socialLinks" :key="socialLink.name" class="social-item">
      <button class="social-link" @click="socialLogin(socialLink.value)">
        <component :is="socialLink.icon" />
        {{ socialLink.name }}
      </button>
    </li>
  </ul>
</template>

<script>
import FacebookIcon from "@/svgs/signup/facebook-icon";
import GoogleIcon from "@/svgs/signup/google-icon";
import socialLoginMixin from "@/mixin/socialLoginMixin";
import AppleIcon from "@/svgs/signup/apple-icon";

export default {
    name: "SocialLogin",
    components: {GoogleIcon, FacebookIcon, AppleIcon},
    data() {
        return {
            socialLinks: [{
                name: "Facebook",
                value: "facebook",
                url: "",
                icon: "FacebookIcon"
            }, {
                name: "Signup with Google",
                value: "google",
                url: "",
                icon: "GoogleIcon"
            },
            {
                name: "Sign in with Apple",
                value: "apple",
                url: "",
                icon: "AppleIcon"
            }]
        };
    },
    mixins: [socialLoginMixin]
};
</script>

<style scoped>

</style>
