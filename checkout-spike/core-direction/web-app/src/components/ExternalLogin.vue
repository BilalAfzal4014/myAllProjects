<template>
  <div class="wrapper">
    <Signin />
    <main id="main">
      <HeaderSignup :is-account-created="false" :is-hide-home-page-link="false" />
      <h1 style="font-size: 40px; padding-top: 90px; padding-bottom: 90px" align="center">
        Please wait we are transferring your session to web application....
      </h1>
      <footer-component />
    </main>
  </div>
</template>

<script>
import HeaderSignup from "@/components/signup/header";
import Signin from "@/partials/modal/signin";
import loginMixin from "@/mixin/loginMixin";
import FooterComponent from "@/partials/footer";
import uploadMediaMixin from "@/mixin/uploadMediaMixin";
import {externalLogin} from "@/apiManager/user";
import * as toastr from "toastr";
import {removePrefixFromObject} from "@/utils";


export default {
    name: "ExternalLogin",
    mixins: [loginMixin, uploadMediaMixin],
    components: {
        FooterComponent,
        Signin,
        HeaderSignup,
    },
    data() {
        return {};
    },

    methods: {

        async externalLogin(token) {
            try {
                localStorage.removeItem("token");
                localStorage.removeItem("userProfile");
                localStorage.removeItem("refreshToken");
                const response = await externalLogin(token);
                let payload = removePrefixFromObject(response.data.user);
                const genderMapping = {
                    m: "Male",
                    f: "Female",
                    u: "Unlisted",
                };
                payload["gender"] =
                  genderMapping[payload["gender"]] || payload["gender"];
                this.$store.dispatch("setUserProfileInformationAction", payload);
                this.$store.dispatch("setStoreTokenAction", response.data.jwtToken);
                this.$store.commit("setRefreshToken", response.data.refreshToken);
                toastr.success(response.message);
                if (this.$route.query.redirect_to !== undefined && this.$route.query.redirect_to !== "undefined" ){
                    this.$router.push(this.$route.query.redirect_to);
                }else {
                    this.$router.push("/community");
                }
            } catch (error) {
                toastr.error(error);
            }
        },


    },
    async mounted() {
        this.$emit("showed", "showAllModal");
    },
    created() {
        if (!this.$route.query.token){
            alert("Unauthorised . Token is not provided");
        }
        this.externalLogin(this.$route.query.token);
    }

};
</script>

<style>
@import "../assets/css/signup.css";
</style>