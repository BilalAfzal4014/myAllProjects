<template>
  <div>
    <p class="signup-title">
      Welcome!
    </p>
    <p v-if="corporateName" class="signup-disc">
      You have been invited to the {{ corporateName }} Wellness Programme, login or sign up to accept the invite.
    </p>
    <p v-else class="signup-disc">
      Please choose your sign up option
    </p>
    <SocialLogin />

    <p class="other-options">
      Or
    </p>
    <div class="signup-form">
      <custom-input v-model="user.email"
                    class-names="input-field"
                    input-id="email"
                    placeholder="Enter your email address"
                    type="email"
                    @badWordErrorChange="handleBadWordErrorChange"
                    @nextStep="nextStep"
      >
        <template #errors>
          <p v-if="hasError" class="error-message flex items-center mb-2">
            <validation-info-icon />
            <span>{{ errorMessage }}</span>
          </p>
        </template>
      </custom-input>
      <button :disabled="isNextButtonDisabled" class="btn-submit" @click="nextStep">
        Sign Up
      </button>
    </div>
  </div>
</template>

<script>
import SocialLogin from "@/components/signup/SocialLogin";
import Joi from "joi";
import {checkEmailExist, getCorporateName} from "@/apiManager/user";
import CustomInput from "@/components/form/custom-input";
import ValidationInfoIcon from "@/svgs/errors/validation-info-icon";

export default {
    name: "MainSignupComponent",
    components: {ValidationInfoIcon, CustomInput, SocialLogin},
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            hasError: false,
            errorMessage: "",
            isNextButtonDisabled: false,
            corporateUserLogin: this.getCorporateUserLogin(),
            corporateName: "",
        };
    },
    created() {
        this.fetchCorporateName();
    },
    methods: {
        getCorporateUserLogin() {
            return JSON.parse(localStorage.getItem("corporate_invite_slug"));
        },
        async fetchCorporateName() {
            if (!this.corporateUserLogin) return;

            try {
                const response = await getCorporateName(this.corporateUserLogin);
                this.corporateName = response.data.comapanyName;
            } catch (error) {
                toastr.error("Error in fetching corporate name:", error);
            }
        },
        validateSchema() {
            this.hasError = false;
            this.errorMessage = "";
            let JoiPayLoad = {
                Email: this.user.email,
            };
            const schema = Joi.object({
                Email: Joi.string()
                    .email({tlds: {allow: false}})
                    .required(),
            });
            const {error} = schema.validate(JoiPayLoad);
            if (error) {
                this.hasError = true;
                this.errorMessage = error.message.replace(/"/g, "");
                return false;
            }
            return true;
        },
        handleBadWordErrorChange(error) {
            this.isNextButtonDisabled = !!error;
        },
        nextStep(e) {
            e.preventDefault();
            if (!this.validateSchema()) {
                return;
            }
            checkEmailExist({email: this.user.email})
                .then((response) => {
                    if (response.data.status) {
                        this.hasError = true;
                        this.errorMessage = "This email is already being used. Please sign in instead.";
                        return;
                    }
                    this.hasError = false;
                    this.errorMessage = "";

                    this.$emit("nextStep", JSON.stringify({email: this.user.email}));
                })
                .catch((error) => toastr.error(error));
        },
    },
};
</script>

<style scoped></style>