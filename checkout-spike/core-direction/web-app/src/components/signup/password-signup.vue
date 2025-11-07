<template>
  <div class="form-fields-box">
    <custom-input v-model="password"
                  :type="passwordType"
                  input-id="password"
                  placeholder="********"
                  @badWordErrorChange="handleBadWordErrorChange"
    >
      <template #label>
        <label class="field-label" for="password">Let's finish with your <strong>password</strong>
        </label>
        <p class="sub-label">
          We recommend you use a password manager to keep things secure.
        </p>
      </template>
      <template #icons>
        <password-show
          v-if="password.length && passwordType === 'password'"
          class="cursor-pointer"
          @showPassword="showAndHidePassword('passwordType')"
        />
        <password-hide
          v-if="password.length && passwordType === 'text'"
          class="cursor-pointer"
          @showPassword="showAndHidePassword('passwordType')"
        />
      </template>
      <template #errors>
        <p v-if="!hasMinLength" class="error-message regix-false flex">
          - Your password must be at least 8 characters long.
        </p>
      </template>
    </custom-input>
    <custom-input v-model="confirmPassword"
                  :type="confirmPasswordType"
                  input-id="confirm-password"
                  placeholder="Confirm your password"
                  @badWordErrorChange="handleBadWordErrorChange"
    >
      <template #icons>
        <password-show
          v-if="confirmPassword.length && confirmPasswordType === 'password'"
          class="cursor-pointer"
          @showPassword="showAndHidePassword('confirmPasswordType')"
        />
        <password-hide
          v-if="confirmPassword.length && confirmPasswordType === 'text'"
          class="cursor-pointer"
          @showPassword="showAndHidePassword('confirmPasswordType')"
        />
      </template>
      <template #errors>
        <p v-if="hasError" class="error-message flex items-center">
          <validation-info-icon />
          <span>{{ errorMessage }}</span>
        </p>
      </template>
    </custom-input>
    <div class="tc-box flex flex-col">
      <div class="terms-conditions form-group">
        <input id="agreeCheckbox" v-model="isCheckboxChecked" type="checkbox">
        <label for="agreeCheckbox">
          By submitting this form you agree to the
          <a :href="externalLinks.TERMS_AND_CONDITIONS" class="tc-link" target="_blank">
            Terms &amp;
            Conditions
          </a>
          and acknowledge you have read our
          <a :href="externalLinks.PRIVACY_POLICY" class="tc-link" target="_blank">
            Privacy
            Policy
          </a>
        </label>
        <p v-if="!isCheckboxChecked && isClicked" class="error-message-red">
          You must agree to the terms and conditions.
        </p>
      </div>
      <custom-checkbox input-id="cd-services"
                       input-name="profile-privacy"
                       input-value="cd-services"
                       @checked-value="updateCheckboxValue"
      >
        I want to receive promotional emails from and about Core Direction products and services.
      </custom-checkbox>
    </div>
    <NextInput :is-hide-press-enter="false" :is-show-text-on-button="true" @nextStep="nextStep"
               @previousStep="$emit('previousStep')"
    />
  </div>
</template>

<script>
import NextInput from "@/components/signup/next-input";
import Joi from "joi";
import PasswordShow from "@/svgs/password/PasswordShow";
import PasswordHide from "@/svgs/password/PasswordHide";
import ValidationInfoIcon from "@/svgs/errors/validation-info-icon";
import CustomCheckbox from "@/components/form/custom-checkbox";
import CustomInput from "@/components/form/custom-input";
import {EXTERNAL_LINKS} from "@/common/constants/constants";

export default {
  name: "PasswordSignup",
  components: {CustomInput, CustomCheckbox, ValidationInfoIcon, PasswordHide, PasswordShow, NextInput},
  data() {
    return {
      hasError: false,
      errorMessage: "",
      password: "",
      passwordType: "password",
      confirmPassword: "",
      confirmPasswordType: "password",
      externalLinks: EXTERNAL_LINKS,
      checkboxValues: {},
      isNextButtonDisabled: false,
      isCheckboxChecked: false,
      isClicked: false,
    };
  },
  computed: {
    hasMinLength() {
      return this.password.length >= 8;
    },
  },
  methods: {
    validateSchema() {
      this.hasError = false;
      this.errorMessage = "";
      let JoiPayLoad = {
        password: this.password,
        confirmPassword: this.confirmPassword,
      };
      const schema = Joi.object({
        password: Joi.string().min(8).required(),
        confirmPassword: Joi.any()
          .valid(Joi.ref("password"))
          .required()
          .messages({
            "any.only": "Password didn't match! please rewrite your password.",
          }),
      });
      const {error} = schema.validate(JoiPayLoad);
      if (error) {
        this.hasError = true;
        this.errorMessage = error;
        return false;
      }
      return true;
    },
    updateCheckboxValue(eventData) {
      this.checkboxValues[eventData.id] = eventData.checked;
    },
    handleBadWordErrorChange(error) {
      this.isNextButtonDisabled = !!error;
    },
    nextStep() {
      this.isClicked = true;
      this.hasError = false;
      this.errorMessage = "";
      if (!this.validateSchema()) {
        return;
      }
      if (this.isNextButtonDisabled) return false;
      if (!this.isCheckboxChecked) {
        return false;
      }

      this.hasError = false;
      this.errorMessage = "";
      this.$emit("nextStep", JSON.stringify({password: this.password}));
    },
    previousStep() {

    },

    showAndHidePassword(type) {
      this[type] = this[type] === "text" ? "password" : "text";
    },

  },
};
</script>

<style scoped>
.error-message-red {
  color: #F14336;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  align-items: flex-start;
}
</style>