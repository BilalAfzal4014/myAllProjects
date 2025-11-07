<template>
  <div class="social-signup-box mb-5 flex items-center justify-center">
    <p class="social-signup-text mr-5">
      Sign up with
    </p>
    <ul class="social-list flex items-center">
      <li class="social-item mr-4">
        <a class="social-link" href="#" @click="loginGoogle">
          <img alt="" src="/assets/images/signup/google.png">
        </a>
      </li>
      <li class="social-item">
        <a class="social-link" href="#" @click="loginFacebook">
          <img alt="" src="/assets/images/signup/facebook.png">
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
import {auth, facebookProvider, googleProvider} from "../../../firebaseConfig";
import * as toastr from "toastr";

export default {
    name: "SignupSocialLogin",
    methods: {
        loginGoogle() {
            const provider = googleProvider;

            auth.signInWithPopup(provider).then((result) => {
                let id = result["additionalUserInfo"]["profile"]["id"];
                let email = result["additionalUserInfo"]["profile"]["email"];
                let type = "google";
                let first_name = result["additionalUserInfo"]["profile"]["given_name"];
                let last_name = result["additionalUserInfo"]["profile"]["family_name"];
                let payload = {
                    id,
                    email,
                    type,
                    first_name,
                    last_name,
                    "need_to_verify_email": 0

                };

                this.oldApi("post",
                    this.constants.getUrl("socialLogin"),
                    payload, true
                ).then((response) => {
                    toastr.success("You have logged in successfully.");

                    this.$store.dispatch("setStoreTokenAction", response.data.jwtToken);
                    this.getUserProfile();

                }).catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
            }).catch(err => toastr.error("The popup has been closed by the user before finalizing the operation."));
        },
        loginFacebook() {
            const provider = facebookProvider;

            auth.signInWithPopup(provider).then((result) => {
                let first_name = result["additionalUserInfo"]["profile"]["name"];
                let last_name = result["additionalUserInfo"]["profile"]["last_name"];
                let id = result["additionalUserInfo"]["profile"]["id"];
                let email = result["additionalUserInfo"]["profile"]["email"];
                let type = "facebook";
                let payload = {
                    id,
                    email,
                    type,
                    first_name,
                    last_name,
                    "need_to_verify_email": 0
                };

                this.oldApi("post",
                    this.constants.getUrl("socialLogin"),
                    payload, true
                ).then((response) => {
                    toastr.success("You have logged in successfully.");
                    this.$store.dispatch("setStoreTokenAction", response.data.jwtToken);
                    this.getUserProfile();
                }).catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });

            }).catch((err) => {
                toastr.error("The popup has been closed by the user before finalizing the operation.");
            });
        },
        getUserProfile() {
            this.oldApi("get",
                this.constants.getUrl("getProfile"), true
            ).then((response) => {
                this.$store.dispatch("setUserProfileInformationAction", response.data);
                if (this.$route.name == "Landing" || this.$route.name == "SignupComponent") {
                    window.location.reload();
                } else {
                    this.$emit("updateState", "login");
                }

            }).catch((error) => {
                toastr.error(error[0].response.data.message);
                // this.$store.dispatch('removeStoreTokenAction');
                // this.$router.push('/')
            });
        }

    }
};
</script>

<style scoped>

</style>