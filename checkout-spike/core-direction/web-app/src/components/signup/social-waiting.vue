<template>
  <div>
    <p class="signup-title">
      Welcome!
    </p>
    <p class="signup-disc">
      {{ message }}
    </p>
  </div>
</template>

<script>

import * as toastr from "toastr";
import {checkUserExist, syncSocialMediaUser} from "@/apiManager/user";
import {removePrefixFromObject} from "@/utils";
import VueJwtDecode from "vue-jwt-decode";

export default {
    name: "SocialWaiting",
    data() {
        return {
            successMessage: "Please wait. We are setting up your account....",
            errorMessage: "You are not authorised to move forward. Please try to login again or contact with support.",
            message: "",
            aws: {
                domain: process.env.AWS_COGNITO_USER_POOL_DOMAIN,
                clientId: process.env.AWS_COGNITO_CLIENT_ID,
                type: "token",
                scope: "openid profile",
                callback: window.location.protocol + "//" + window.location.host + "/aws-cognito-social",

                response: null
            },
            hasError: false,
        };
    },
    created() {
        this.showLoader();
        this.lookForCodeInQueryParams();
        this.hideLoader();
    },
    methods: {
        async processSocialLogin() {
            let payload = VueJwtDecode.decode(this.aws.response.id_token);
            payload = removePrefixFromObject(payload);
            if (payload.local_user_id === undefined && payload.firstname === undefined && payload.lastname === undefined) {
                this.$router.push("/signup-social?social_success=true&step=2&token=" + this.aws.response.id_token);
            } else if (payload.local_user_id === undefined) {
                this.$router.push("/signup-social?social_success=true&step=4&token=" + this.aws.response.id_token);
            } else {
                let isUserExist = await checkUserExist(this.aws.response);
                if (isUserExist.status === "askMoreInfo") {
                    this.$router.push("/signup-social?social_success=true&step=4&token=" + this.aws.response.id_token);
                } else {
                    syncSocialMediaUser(this.aws.response).then((response) => {
                        toastr.success("You have logged in successfully.");
                        let payload = removePrefixFromObject(response.data.user);
                        if (payload["gender"] === "m") {
                            payload["gender"] = "Male";
                        }

                        if (payload["gender"] === "f") {
                            payload["gender"] = "Female";
                        }

                        if (payload["gender"] === "u") {
                            payload["gender"] = "Unlisted";
                        }
                        this.$store.dispatch("setUserProfileInformationAction", response.data.user);
                        this.$store.dispatch("setStoreTokenAction", response.data.jwtToken);
                        if (payload.is_profile_completed) {
                            window.location = "/";
                        } else {
                            this.$router.push("/signup-social?social_success=true&step=9&token=" + response.data.jwtToken);
                        }
                    });
                }

            }
        },

        lookForCodeInQueryParams() {
            const parsedParams = {};
            this.$route.hash.split("&")
                .map(part => part.replace(/^#/, ""))
                .forEach(param => {
                    const parts = param.split("=");
                    parsedParams[parts[0]] = parts[1];
                });
            this.aws.response = parsedParams;
            if (this.aws.response.access_token) {
                toastr.info("Please wait. Validating your authentication request.");
                this.message = this.successMessage;
                this.processSocialLogin();
            } else {
                toastr.error("Forbidden Request. ");
                this.message = this.errorMessage;

            }

        },

        showLoader() {
            $("body").addClass("overflow-y-hidden");
            $(".ajax_loader").show();
        },
        hideLoader() {
            $("body").addClass("overflow-y-hidden");
            $(".ajax_loader").hide();
        }
    },
};
</script>

<style scoped></style>