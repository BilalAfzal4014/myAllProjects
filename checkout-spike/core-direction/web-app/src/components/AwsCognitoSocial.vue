<template>
  <div class="wrapper">
    <Signin />
    <main id="main">
      <section :id="`${isAccountCreated ? 'additional-info':'sign-up'}`"
               :class="`${isAccountCreated ? '':'background-image'}`"
      >
        <HeaderSignup :is-account-created="true" :is-hide-home-page-link="isHideHomePageLink" />
        <div :class="`custom-container ${isProfileCompletedPage ? '' :'signup-body'}`">
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
        <FooterSignup v-if="!isAccountCreated" />
      </section>
    </main>
  </div>
</template>

<script>
import HeaderSignup from "@/components/signup/header";
import SocialWaiting from "@/components/signup/social-waiting";
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
import * as toastr from "toastr";
import UsernameSignup from "@/components/signup/username-signup";
import Signin from "@/partials/modal/signin";
import loginMixin from "@/mixin/loginMixin";
import FooterComponent from "@/partials/footer";
import {updateMetaInformation} from "@/utils";

export default {
  name: "SignupComponent",
  components: {
    FooterComponent,
    Signin,
    AdditionalInformationSignup,
    PasswordSignup,
    DateOfBirthSignup,
    SelectGenderSignup,
    PhoneNumberSignup,
    LastNameSignup, PageStatus, FirstNameSignup, SocialWaiting, FooterSignup, HeaderSignup, UsernameSignup,
    SetUserProfile

  },
  mixins: [loginMixin],
  data() {
    return {
      step: 1,
      activeComponent: SocialWaiting,
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
        "privacy": "public"
      },
      isHideHomePageLink: true,
    };
  },
  methods: {
    previousStep() {
      if (this.step === 1) return false;
      this.step--;
      if (this.step === 1) this.isHideHomePageLink = true;
      this.activeComponent = this.getCurrentStep(`step${this.step}`);
    },
    nextStep(data) {
      this.isHideHomePageLink = false;
      Object.assign(this.registerUserPayload, JSON.parse(data));
      if (this.step === 8) {
        registerUser(this.registerUserPayload).then((response) => {
          toastr.success(response.message);
          this.step = this.step + 1;
          this.isAccountCreated = true;
          this.signin(this.registerUserPayload.username, this.registerUserPayload.password);
          this.activeComponent = this.getCurrentStep(`step${this.step}`);
        }).catch(error => toastr.error(error));
        return false;
      }
      ;
      this.step = this.step + 1;
      this.activeComponent = this.getCurrentStep(`step${this.step}`);
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
    }


  },
  mounted() {
    this.$emit("showed", "showAllModal");
    updateMetaInformation("Sign-up | Core Direction", "", "Sign-up to Core Direction and start #inspiringmovement", "Sign-up | Core Direction", "Sign-up to Core Direction and start #inspiringmovement", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/signup");
  }
};
</script>

<style>
#sign-up {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  min-height: 100vh;
  background-color: #F2F5EA;
  background-image: url("/assets/images/bg-signup.png");
  background-repeat: no-repeat;
  background-size: 250px;
  background-position: left top;
}

@media screen and (max-width: 767px) {
  #sign-up {
    background-size: 125px;
  }
}

#sign-up #signup-header {
  padding-top: 36px;
}

@media screen and (max-width: 767px) {
  #sign-up #signup-header {
    padding-top: 22px;
  }
}

#sign-up #signup-header .brand-img {
  width: 100%;
  max-width: 218px;
}

@media screen and (max-width: 767px) {
  #sign-up #signup-header .brand-img {
    max-width: 137px;
  }
}

@media screen and (max-width: 374px) {
  #sign-up #signup-header .brand-img {
    max-width: 100px;
  }
}

#sign-up #signup-header .home-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #sign-up #signup-header .home-link {
    font-size: 13px;
    font-weight: 600;
  }
}

#sign-up #signup-header .btn-signin {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  background-color: #690FAD;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  padding: 11px 32px;
}

@media screen and (max-width: 767px) {
  #sign-up #signup-header .btn-signin {
    font-size: 13px;
    font-weight: 600;
  }
}

@media screen and (max-width: 389px) {
  #sign-up #signup-header .btn-signin {
    padding: 7px 20px;
  }
}

#sign-up #signup-header .signin-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #sign-up #signup-header .signin-link {
    font-size: 13px;
    line-height: 16px;
  }
}

#sign-up .signup-form {
  width: 100%;
  max-width: 391px;
  margin-left: auto;
  margin-right: auto;
  padding-bottom: 100px;
}

#sign-up .signup-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 36px;
  font-weight: 600;
  line-height: 44px;
  letter-spacing: 0em;
  text-align: center;
  margin-bottom: 15px;
  padding-top: 43px;
}

@media screen and (max-width: 767px) {
  #sign-up .signup-title {
    font-size: 24px;
    line-height: 29px;
  }
}

#sign-up .signup-disc {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: center;
  margin-bottom: 59px;
}

@media screen and (max-width: 767px) {
  #sign-up .signup-disc {
    font-size: 14px;
    line-height: 17px;
    margin-bottom: 47px;
  }
}

#sign-up .social-list {
  width: 100%;
  max-width: 450px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-bottom: 46px;
  -webkit-column-gap: 11px;
  column-gap: 11px;
  row-gap: 11px;
}

@media screen and (max-width: 767px) {
  #sign-up .social-list {
    margin-bottom: 31px;
  }
}

#sign-up .social-list li {
  position: relative;
}

#sign-up .social-list .social-link {
  width: 100%;
  max-width: 147px;
  min-width: 130px;
  height: 50px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #000000;
  background-color: #FFFFFF;
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  border-radius: 12px;
  -webkit-border-radius: 12px;
  -moz-border-radius: 12px;
  -ms-border-radius: 12px;
  -o-border-radius: 12px;
  padding: 11px 9px;
}

#sign-up .social-list .social-link svg,
#sign-up .social-list .social-link img {
  margin-right: 16px;
}

@media screen and (max-width: 767px) {
  #sign-up .social-list .social-link {
    font-size: 11px;
    line-height: 13px;
    padding: 9px;
    max-width: 109px;
    min-width: 102px;
  }

  #sign-up .social-list .social-link svg,
  #sign-up .social-list .social-link img {
    margin-right: 13px;
  }
}

@media screen and (max-width: 767px) {
  #sign-up .social-list .social-link svg,
  #sign-up .social-list .social-link img {
    margin-right: 9px;
    max-height: 17px;
  }
}

#sign-up .other-options {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  margin-bottom: 40px;
}

@media screen and (max-width: 767px) {
  #sign-up .other-options {
    font-size: 14px;
    line-height: 17px;
    margin-bottom: 15px;
  }
}

#sign-up .input-field {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px !important;
  font-weight: 300;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  padding: 15px 22px;
  border: 1px solid #690FAD;
  border-radius: 7px;
  background-color: #FFFFFF;
  display: block;
  width: 100%;
  height: 45px;
  margin-bottom: 15px;
}

@media screen and (max-width: 767px) {
  #sign-up .input-field {
    font-size: 14px !important;
    line-height: 17px;
    padding: 13px 18px;
  }
}

#sign-up .btn-submit {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  padding: 15px;
  color: #FFFFFF;
  background-color: #690FAD;
  display: block;
  width: 100%;
  border-radius: 12px;
}

@media screen and (max-width: 767px) {
  #sign-up .btn-submit {
    font-size: 14px;
    padding: 12px;
  }
}

.btn-navigate {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  padding: 15px;
  color: #FFFFFF;
  background-color: #690FAD;
  display: block;
  width: 100%;
  border-radius: 12px;
  max-width: 238px;
}

#sign-up .form-fields-box {
  width: 100%;
  max-width: 1040px;
  margin-left: auto;
  padding-top: 134px;
  padding-bottom: 80px;
}

@media screen and (max-width: 767px) {
  #sign-up .form-fields-box {
    padding-top: 211px;
    padding-bottom: 119px;
  }
}

@media screen and (max-width: 389px) {
  #sign-up .form-fields-box {
    padding-top: 125px;
  }
}

#sign-up .form-fields-box .field-box {
  margin-bottom: 30px;
}

#sign-up .form-fields-box .field-label {
  display: block;
  font-family: 'Montserrat', sans-serif;
  font-size: 32px;
  font-weight: 500;
  line-height: 39px;
  letter-spacing: 0em;
  text-align: left;
  max-width: 846px;
  margin-bottom: 20px;
}

@media screen and (max-width: 767px) {
  #sign-up .form-fields-box .field-label {
    font-size: 18px;
    line-height: 22px;
  }
}

#sign-up .form-fields-box .field-label strong {
  font-weight: 600;
}

#sign-up .form-fields-box .field-label span {
  font-weight: 300;
}

#sign-up .form-fields-box .sub-label {
  display: block;
  max-width: 846px;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 500;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 34px;
  margin-top: -10px;
}

@media screen and (max-width: 767px) {
  #sign-up .form-fields-box .sub-label {
    font-size: 12px;
    line-height: 15px;
    margin-bottom: 20px;
  }
}

#sign-up .form-fields-box .input-field {
  padding: 0 0 15px;
  border: none;
  border-bottom: 1px solid #690FAD;
  border-radius: 0;
  -webkit-border-radius: 0;
  -moz-border-radius: 0;
  -ms-border-radius: 0;
  -o-border-radius: 0;
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 400;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
  background: transparent;
  max-width: 606px;
}

@media screen and (max-width: 767px) {
  #sign-up .form-fields-box .input-field {
    font-size: 18px;
    line-height: 22px;
  }
}

#sign-up .form-fields-box .error-message {
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #06070E;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-top: 10px;
  padding-left: 16px;
}

@media screen and (max-width: 767px) {
  #sign-up .form-fields-box .error-message {
    font-size: 12px;
    line-height: 15px;
    padding-left: 10px;
  }
}

#sign-up .form-fields-box .error-message svg,
#sign-up .form-fields-box .error-message img {
  margin-right: 5px;
}

#sign-up .form-fields-box .error-message.regix-false,
#sign-up .form-fields-box .error-message.regix-true {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  margin-top: 0;
}

@media screen and (max-width: 767px) {
  #sign-up .form-fields-box .error-message.regix-false,
  #sign-up .form-fields-box .error-message.regix-true {
    font-size: 10px;
    padding-left: 0;
  }
}

#sign-up .form-fields-box .error-message.regix-false {
  color: #DE1414;
}

#sign-up .form-fields-box .error-message.regix-false svg, #sign-up .form-fields-box .error-message.regix-false path {
  fill: #DE1414;
}

#sign-up .form-fields-box .error-message.regix-true {
  color: #28B446;
}

#sign-up .form-fields-box .error-message.regix-true svg, #sign-up .form-fields-box .error-message.regix-true path {
  fill: #28B446;
}

#sign-up .form-fields-box .btn-submit {
  max-width: 101px;
  margin-right: 21px;
}

#sign-up .form-fields-box .btn-submit svg,
#sign-up .form-fields-box .btn-submit img {
  margin: auto;
}

#sign-up .form-fields-box .btn-register {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  padding: 15px;
  color: #FFFFFF;
  background: #690FAD;
  width: 100%;
  max-width: 238px;
  border-radius: 7px;
}

@media (max-width: 767px) {
  #sign-up .form-fields-box .btn-register {
    max-width: 238px;
    font-size: 13px;
    line-height: 16px;
    padding: 13px;
  }
}

#sign-up .form-fields-box .note {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 300;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}

#sign-up .error-message {
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  color: #06070E;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-top: 10px;
  padding-left: 16px;
}

@media screen and (max-width: 767px) {
  #sign-up .error-message {
    font-size: 12px;
    line-height: 15px;
    padding-left: 10px;
  }
}

#sign-up .error-message svg,
#sign-up .error-message img {
  margin-right: 5px;
}

#sign-up .error-message.regix-false {
  color: #F14336;
}

#sign-up .error-message.regix-false svg, #sign-up .error-message.regix-false path {
  fill: #F14336;
}

#sign-up .error-message.regix-true {
  color: #690FAD;
}

#sign-up .error-message.regix-true svg, #sign-up .error-message.regix-true path {
  fill: #690FAD;
}

#sign-up .pagination-list {
  width: 100%;
  max-width: 1040px;
  margin-left: auto;
  margin-bottom: 38px;
}

@media screen and (max-width: 767px) {
  #sign-up .pagination-list {
    margin-bottom: 30px;
  }
}

#sign-up .btn-back {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 0;
}

#sign-up .btn-back svg {
  margin-right: 13px;
}

@media screen and (max-width: 767px) {
  #sign-up .btn-back {
    font-size: 15px;
    line-height: 18px;
  }
}

@media screen and (max-width: 389px) {
  #sign-up .btn-back {
    font-size: 14px;
  }

  #sign-up .btn-back svg {
    margin-right: 10px;
    max-height: 18px;
  }
}

#sign-up .page-counter {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #sign-up .page-counter {
    font-size: 14px;
    line-height: 17px;
  }
}

#sign-up #signup-footer {
  padding: 15px 15px 18px;
  background-color: #690FAD;
}

#sign-up #signup-footer .copyright {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #FFFFFF;
}

@media (max-width: 767px) {
  #sign-up #signup-footer .copyright {
    font-size: 12px;
  }
}

#additional-info {
  min-height: 100vh;
}

#additional-info #signup-header {
  padding-top: 36px;
}

@media screen and (max-width: 767px) {
  #additional-info #signup-header {
    padding-top: 22px;
  }
}

#additional-info #signup-header .brand-img {
  width: 100%;
  max-width: 218px;
}

@media screen and (max-width: 767px) {
  #additional-info #signup-header .brand-img {
    max-width: 137px;
  }
}

#additional-info #signup-header .home-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #additional-info #signup-header .home-link {
    font-size: 13px;
    font-weight: 600;
  }
}

#additional-info #signup-header .btn-signin {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  background-color: #690FAD;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  padding: 11px 32px;
}

@media screen and (max-width: 767px) {
  #additional-info #signup-header .btn-signin {
    font-size: 13px;
    font-weight: 600;
  }
}

#additional-info #signup-header .signin-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #additional-info #signup-header .signin-link {
    font-size: 13px;
    line-height: 16px;
  }
}

#additional-info .success-message-box {
  width: 100%;
  max-width: 1063px;
  padding: 69px 15px 94px;
  background: #690FAD;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 11px;
  margin-left: auto;
  margin-right: auto;
  margin-top: 142px;
  margin-bottom: 170px;
}

@media screen and (max-width: 767px) {
  #additional-info .success-message-box {
    margin-top: 45px;
    margin-bottom: 20px;
    padding: 60px 15px 58px;
  }
}

#additional-info .success-icon {
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 30px;
}

@media screen and (max-width: 767px) {
  #additional-info .success-icon {
    margin-bottom: 20px;
  }
}

#additional-info .success-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 700;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  margin-bottom: 15px;
}

@media screen and (max-width: 767px) {
  #additional-info .success-title {
    font-size: 18px;
    line-height: 21px;
  }
}

#additional-info .success-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  margin-bottom: 67px;
}

@media screen and (max-width: 767px) {
  #additional-info .success-subtitle {
    font-size: 14px;
    line-height: 17px;
    margin-bottom: 111px;
  }
}

#additional-info .additional-info-link {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  font-family: 'Montserrat', sans-serif;
  font-weight: 500;
  font-size: 32px;
  line-height: 39px;
  text-align: center;
  color: #F2F5EA;
  margin-bottom: 64px;
}

@media screen and (max-width: 767px) {
  #additional-info .additional-info-link {
    font-size: 18px;
    line-height: 22px;
    margin-bottom: 30px;
  }
}

#additional-info .additional-info-link svg {
  margin-left: 15px;
}

#additional-info .btn-close {
  display: block;
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  margin-bottom: 61px;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-close {
    font-size: 16px;
    line-height: 20px;
    margin-bottom: 111px;
  }
}

#additional-info .success-desc {
  max-width: 548px;
  margin-left: auto;
  margin-right: auto;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: italic;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
}

@media screen and (max-width: 767px) {
  #additional-info .success-desc {
    font-size: 10px;
    line-height: 12px;
  }
}

#additional-info .addition-info-box {
  width: 100%;
  max-width: 709px;
  margin-top: 60px;
}

@media screen and (max-width: 767px) {
  #additional-info .addition-info-box {
    margin-top: 44px;
  }
}

#additional-info .addition-info-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 32px;
  font-weight: 600;
  line-height: 39px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 20px;
}

@media screen and (max-width: 767px) {
  #additional-info .addition-info-title {
    font-size: 18px;
    line-height: 22px;
    margin-bottom: 10px;
  }
}

#additional-info .addition-info-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 40px;
  color: rgba(0, 0, 0, 0.45);
}

@media screen and (max-width: 767px) {
  #additional-info .addition-info-desc {
    font-size: 12px;
    line-height: 16px;
    margin-bottom: 52px;
  }
}

#additional-info .section-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
}

#additional-info .section-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-top: 20px;
  color: #00000073;
}

#additional-info .avatar-box {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 43px;
  column-gap: 43px;
  row-gap: 43px;
  margin-top: 30px;
  margin-bottom: 60px;
}

@media screen and (max-width: 767px) {
  #additional-info .avatar-box {
    -webkit-column-gap: 30px;
    column-gap: 30px;
    row-gap: 30px;
  }
}

#additional-info .avatar-img-box {
  min-width: 168px;
  min-height: 168px;
  max-width: 168px;
  max-height: 168px;
  border: 2px dashed #00000073;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  overflow: hidden;
}

@media screen and (max-width: 767px) {
  #additional-info .avatar-img-box {
    min-width: 100px;
    min-height: 100px;
    max-width: 100px;
    max-height: 100px;
  }
}

#additional-info .avatar-img-box img {
  min-width: 168px;
  min-height: 168px;
  max-width: 168px;
  max-height: 168px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  -o-object-fit: fill;
  object-fit: fill;
}

@media screen and (max-width: 767px) {
  #additional-info .avatar-img-box img {
    min-width: 100px;
    min-height: 100px;
    max-width: 100px;
    max-height: 100px;
  }
}

#additional-info .btn-upload-img {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
  padding: 11px 16px 12px;
  border: 1px solid #000000;
  border-radius: 11px;
  color: #06070E;
}

#additional-info .btn-upload-img.active {
  border: 1px solid #690FAD;
  background: #690FAD;
  color: #FFFFFF;
  font-weight: 500;
}

#additional-info .bio-box {
  margin-bottom: 60px;
}

#additional-info .bio-field-box {
  position: relative;
  width: 100%;
  max-width: 652px;
  margin-top: 21px;
}

#additional-info .bio-input-filed {
  width: 100%;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  padding-top: 13px;
  padding-bottom: 13px;
  padding-right: 90px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.45);
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

@media screen and (max-width: 767px) {
  #additional-info .bio-input-filed {
    font-size: 14px;
    line-height: 17px;
  }
}

#additional-info .counter {
  position: absolute;
  right: 15px;
  bottom: 13px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #06070E;
}

@media screen and (max-width: 767px) {
  #additional-info .counter {
    font-size: 12px;
    line-height: 15px;
  }
}

#additional-info .privacy-policy-box {
  margin-bottom: 90px;
}

#additional-info .account-privacy-list {
  margin-top: 34px;
  -webkit-column-gap: 60px;
  column-gap: 60px;
  row-gap: 20px;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

@media screen and (max-width: 767px) {
  #additional-info .account-privacy-list {
    -webkit-column-gap: 30px;
    column-gap: 30px;
  }
}

#additional-info .account-privacy-item {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

#additional-info .account-privacy-item label {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 19px;
  letter-spacing: 0em;
  text-align: left;
  color: #06070E;
}

#additional-info .account-privacy-item input {
  width: 24px;
  height: 24px;
  background: #fff;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

#additional-info .account-privacy-item input::after {
  background: #690FAD;
  min-width: 24px;
  min-height: 24px;
  max-width: 24px;
  max-height: 24px;
  left: unset;
  top: unset;
}

#additional-info .tooltip-box {
  margin-left: 15px;
}

@media screen and (min-width: 992px) {
  #additional-info .tooltip-box {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
  }
}

#additional-info .tooltip-content-box {
  display: block;
  position: absolute;
  left: 60px;
  top: 30%;
  -webkit-transform: translateY(-30%);
  transform: translateY(-30%);
  width: 268px;
  padding: 13px 12px 16px 14px;
  background: #FFFFFF;
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25), 0px -1px 4px rgba(0, 0, 0, 0.25), 1px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25), 0px -1px 4px rgba(0, 0, 0, 0.25), 1px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 11px;
}

#additional-info .tooltip-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 10px;
}

#additional-info .tooltip-desc:last-child {
  margin-bottom: 0;
}

#additional-info .tooltip-box.active .tooltip-content-box {
  display: block;
}

#additional-info .tooltip-box.active button.btn-tooltip::after {
  position: absolute;
  content: "";
  width: 38px;
  height: 35px;
  background: #FFFFFF;
  top: 7px;
  bottom: 50%;
  -webkit-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  right: -65px;
  -webkit-clip-path: polygon(100% 0, 0 50%, 100% 100%);
  clip-path: polygon(100% 0, 0 50%, 100% 100%);
  z-index: 1;
  -webkit-filter: drop-shadow(-2px 0px 1px #D6D6D6);
  filter: drop-shadow(-2px 0px 1px #D6D6D6);
}

#additional-info .add-friend-box {
  margin-bottom: 60px;
  width: 100%;
  max-width: 652px;
}

#additional-info .friend-search-header {
  margin-top: 30px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 9px;
  column-gap: 9px;
  margin-bottom: 30px;
}

#additional-info .friend-input-field {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  padding: 14px 27px;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  width: 100%;
  background-color: #F1F1F1;
}

@media screen and (max-width: 767px) {
  #additional-info .friend-input-field {
    font-size: 12px;
    line-height: 15px;
  }
}

#additional-info .btn-find-friend {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  color: #FFFFFF;
  background-color: #690FAD;
  padding: 11px 43px;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-find-friend {
    padding: 11px 29px;
  }
}

#additional-info .friend-item {
  width: 100%;
  -webkit-column-gap: 15px;
  column-gap: 15px;
  margin-bottom: 30px;
}

@media screen and (max-width: 767px) {
  #additional-info .friend-item {
    -webkit-column-gap: 10px;
    column-gap: 10px;
    margin-bottom: 15px;
  }
}

#additional-info .friend-img {
  min-width: 88px;
  min-height: 88px;
  max-width: 88px;
  max-height: 88px;
  -webkit-box-shadow: 0px 4px 5px -2px rgba(232, 183, 95, 0.45);
  box-shadow: 0px 4px 5px -2px rgba(232, 183, 95, 0.45);
  -o-object-fit: fill;
  object-fit: fill;
}

@media screen and (max-width: 767px) {
  #additional-info .friend-img {
    min-width: 60px;
    min-height: 60px;
    max-width: 60px;
    max-height: 60px;
  }
}

#additional-info .friend-info {
  width: 100%;
  word-break: break-all;
}

#additional-info .user-handle {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 8px;
}

@media screen and (max-width: 767px) {
  #additional-info .user-handle {
    font-size: 12px;
    line-height: 15px;
    margin-bottom: 5px;
  }
}

#additional-info .user-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 8px;
}

@media screen and (max-width: 767px) {
  #additional-info .user-name {
    font-size: 12px;
    line-height: 15px;
    margin-bottom: 5px;
  }
}

#additional-info .user-status {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}

@media screen and (max-width: 767px) {
  #additional-info .user-status {
    font-size: 10px;
    line-height: 10px;
  }
}

#additional-info .btn-action,
#additional-info .btn-add-friend {
  min-width: 162px;
  max-width: 162px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  padding: 11px;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-action,
  #additional-info .btn-add-friend {
    min-width: 114px;
    max-width: 114px;
    font-size: 12px;
    line-height: 15px;
  }
}

#additional-info .btn-action {
  background-color: #690FAD;
}

#additional-info .btn-add-friend {
  background-color: #CAA8F5;
}

#additional-info .interest-box {
  margin-bottom: 60px;
}

#additional-info .interest-box .section-title {
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
}

#additional-info .btn-add-activity-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 15px;
  column-gap: 15px;
  row-gap: 20px;
  margin-top: 56px;
}

#additional-info .btn-add-activity-list .btn-filter-activity-type-item {
  margin: 0;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-add-activity-list {
    margin-top: 30px;
    -webkit-column-gap: 20px;
    column-gap: 20px;
    row-gap: 15px;
  }
}

@media screen and (max-width: 389px) {
  #additional-info .btn-add-activity-list {
    -webkit-column-gap: 13px !important;
    column-gap: 13px !important;
  }
}

#additional-info .btn-filter-activity-type {
  -webkit-box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 1px 4px rgba(0, 0, 0, 0.25);
  height: 116px;
}

#additional-info .btn-box {
  -webkit-column-gap: 60px;
  column-gap: 60px;
  margin-bottom: 60px;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-box {
    -webkit-column-gap: 30px;
    column-gap: 30px;
  }
}

#additional-info .btn-submit {
  width: 100%;
  max-width: 238px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFF;
  background-color: #690FAD;
  border-radius: 7px;
  padding: 15px;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-submit {
    max-width: 160px;
    font-size: 14px;
    line-height: 17px;
  }
}

#additional-info .btn-disabled {
  background-color: #CAA8F5;
}

#additional-info .btn-skip {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 500;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #additional-info .btn-skip {
    font-size: 14px;
    line-height: 17px;
  }

  #sign-up .form-fields-box .btn-submit {
    max-width: 90px;
    margin-right: 15px;
  }
}

#sign-up .tc-box {
  row-gap: 30px;
  margin-bottom: 60px;
}

#sign-up .tc-box label {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 389px) {
  #sign-up .tc-box label {
    font-size: 13px;
    line-height: 18px;
  }
}

#sign-up .tc-box .form-group input:checked + label:after {
  top: 1px;
}

#sign-up .tc-notice {
  font-family: 'Montserrat', sans-serif;
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 36px;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 389px) {
  #sign-up .tc-notice {
    font-size: 13px;
    line-height: 24px;
  }
}

#sign-up .tc-link {
  color: #690FAD;
  font-weight: 600;
  text-decoration: underline;
}

#sign-up select {
  -moz-appearance: none !important;
  -webkit-appearance: none !important;
  appearance: none !important;
}
</style>