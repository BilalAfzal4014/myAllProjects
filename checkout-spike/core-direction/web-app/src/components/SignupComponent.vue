<template>
  <div class="wrapper">
    <Signin />
    <SocialSharingOnSignup />
    <ForgetPassword />
    <RecoverPassword />
    <main id="main">
      <section :id="sectionId" :class="sectionClass">
        <HeaderSignup :is-account-created="isAccountCreated" :is-hide-home-page-link="isHideHomePageLink" />
        <div :class="customContainerClass">
          <keep-alive>
            <component :is="activeComponent" :password="registerUserPayload.password"
                       :user="registerUserPayload"
                       :username="registerUserPayload.username"
                       @nextStep="nextStep"
                       @previousStep="previousStep"
            />
          </keep-alive>
          <page-status v-if="step > 1 && !isAccountCreated && activeComponent !== 'PasswordSignup'" :step="step"
                       :total-steps="totalSteps"
                       @previousStep="previousStep"
          />
        </div>
        <footer-component v-if="isProfileCompletedPage" />
        <FooterSignup v-else-if="!isAccountCreated" />
      </section>
    </main>
  </div>
</template>

<script>
import HeaderSignup from "@/components/signup/header";
import MainSignupComponent from "@/components/signup/main";
import FooterSignup from "@/components/signup/footer";
import FirstNameSignup from "@/components/signup/first-name";
import PageStatus from "@/components/signup/page-status";
import LastNameSignup from "@/components/signup/last-name";
import PhoneNumberSignup from "@/components/signup/phone-number-signup";
import SelectGenderSignup from "@/components/signup/select-gender-signup";
import SetUserProfile from "@/components/signup/set-user-profile";
import DateOfBirthSignup from "@/components/signup/date-of-birth-signup";
import PasswordSignup from "@/components/signup/password-signup";
import AdditionalInformationSignup from "@/components/signup/additional-information-signup";
import {registerUser} from "@/apiManager/user";
import UsernameSignup from "@/components/signup/username-signup";
import Signin from "@/partials/modal/signin";
import loginMixin from "@/mixin/loginMixin";
import FooterComponent from "@/partials/footer";
import VueJwtDecode from "vue-jwt-decode";
import {removePrefixFromObject, updateMetaInformation} from "@/utils";
import uploadMediaMixin from "@/mixin/uploadMediaMixin";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import ForgetPassword from "@/partials/ForgetPassword";
import RecoverPassword from "@/partials/RecoverPassword";


export default {
  name: "SignupComponent",
  components: {
    RecoverPassword,
    ForgetPassword,
    SocialSharingOnSignup,
    FooterComponent,
    Signin,
    AdditionalInformationSignup,
    PasswordSignup,
    DateOfBirthSignup,
    SelectGenderSignup,
    PhoneNumberSignup,
    LastNameSignup, PageStatus, FirstNameSignup, MainSignupComponent, FooterSignup, HeaderSignup, UsernameSignup,
    SetUserProfile

  },
  mixins: [loginMixin, uploadMediaMixin],

  data() {
    return {
      step: 1,
      activeComponent: MainSignupComponent,
      totalSteps: 8,
      isAccountCreated: false,
      isProfileCompletedPage: false,
      registerUserPayload: {
        "email": "",
        "username": "",
        "firstname": "",
        "lastname": "",
        "phone": "",
        "password": "",
        "emergency_email": "",
        "emergency_firstname": "",
        "emergency_lastname": "",
        "emergency_phone": "",
        "address": "",
        "locale": "",
        "picture": "",
        "gender": "",
        "profile": "",
        "birthday": "",
        "privacy": "public",
        "cognito_username": "",
        "is_social_user": false,
        "is_onboarded": true,
        "is_profile_completed": false,
      },
      isHideHomePageLink: true,
    };
  },

  computed: {
    sectionId() {
      return this.isAccountCreated ? "additional-info" : "sign-up";
    },
    sectionClass() {
      return this.isAccountCreated ? "" : "background-image";
    },
    customContainerClass() {
      return `custom-container ${this.isProfileCompletedPage ? "" : "signup-body"}`;
    }
  },

  methods: {
    previousStep() {
      if (this.step === 1) return false;
      this.step--;
      this.isHideHomePageLink = this.step === 1;
      this.activeComponent = this.getCurrentStep(`step${this.step}`);
    },
    async nextStep(data) {
      this.isHideHomePageLink = false;
      Object.assign(this.registerUserPayload, JSON.parse(data));
      if (this.step === 8) {
        const imagePath = await this.convertImagePathToFileObject(this.registerUserPayload.gender);
        this.registerUserPayload.picture = imagePath;
        await this.registerUser();
        this.isAccountCreated = true;
        this.signin(this.registerUserPayload.username, this.registerUserPayload.password);
      }
      this.step += 1;
      this.activeComponent = this.getCurrentStep(`step${this.step}`);
    },

    async registerUser() {
      try {
        const data = await registerUser(this.registerUserPayload);
        toastr.success(data.message);
      } catch (error) {
        if (error) toastr.error(error);
      }
    },
    getCurrentStep(step) {
      return {
        step1: "MainSignupComponent",
        step2: "FirstNameSignup",
        step3: "LastNameSignup",
        step4: "UsernameSignup",
        step5: "PhoneNumberSignup",
        step6: "SelectGenderSignup",
        step7: "DateOfBirthSignup",
        step8: "PasswordSignup",
        step9: "AdditionalInformationSignup",
      }[step];
    },
    updateRegisterUserPayload(payload) {
      const removePrefix = removePrefixFromObject(payload);
      this.registerUserPayload.email = removePrefix.email;
      this.registerUserPayload.firstname = removePrefix.firstname;
      this.registerUserPayload.lastname = removePrefix.lastname;
      this.registerUserPayload.cognito_username = removePrefix.username;
    }


  },
  mounted() {
    this.$emit("showed", "showAllModal");
    updateMetaInformation("Sign-up | Core Direction", "", "Sign-up to Core Direction and start #inspiringmovement", "Sign-up | Core Direction", "Sign-up to Core Direction and start #inspiringmovement", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/signup");

  },
  created() {
    const {social_success: isSocialSuccess, step: requiredStep} = this.$route.query;
    let payload = VueJwtDecode.decode(this.$route.query.token);
    this.updateRegisterUserPayload(payload);
    const stepConfig = {
      "4": {activeComponent: "step4", step: 4, isAccountCreated: false},
      "2": {activeComponent: "step2", step: 2, isAccountCreated: false},
      "9": {activeComponent: "step9", step: 9, isAccountCreated: true},
    };
    if (isSocialSuccess || requiredStep === "9") {
      if (stepConfig.hasOwnProperty(requiredStep)) {
        this.registerUserPayload.is_social_user = Boolean(isSocialSuccess);
        this.isHideHomePageLink = false;
        this.activeComponent = this.getCurrentStep(stepConfig[requiredStep].activeComponent);
        this.step = stepConfig[requiredStep].step;
        this.isAccountCreated = stepConfig[requiredStep].isAccountCreated;
      }
    }
  }

};
</script>

<style>
@import "../assets/css/signup.css";
</style>