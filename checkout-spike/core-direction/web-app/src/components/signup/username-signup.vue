<template>
  <div class="form-fields-box">
    <custom-input v-model.trim="username"
                  class-names="input-field"
                  input-id="username"
                  placeholder="username"
                  type="text"
                  @badWordErrorChange="handleBadWordErrorChange"
                  @nextStep="nextStep"
    >
      <template #label>
        <label class="field-label" for="lName">Now create your
          <strong>username&nbsp;</strong>
          <span>and social handle*</span><br>
        </label>
        <p class="sub-label">
          Use underscores "_" instead of space between word/letters
        </p>
      </template>
      <template #errors>
        <p v-if="hasError" class="error-message flex items-center">
          <validation-info-icon />
          <span>{{ errorMessage }}</span>
        </p>
      </template>
    </custom-input>
    <NextInput @nextStep="nextStep" />
  </div>
</template>

<script>
import NextInput from "@/components/signup/next-input";
import Joi from "joi";
import {usernameExist} from "@/apiManager/user";
import CustomInput from "@/components/form/custom-input";
import ValidationInfoIcon from "@/svgs/errors/validation-info-icon";

export default {
    name: "UsernameSignup",
    components: {ValidationInfoIcon, CustomInput, NextInput},
    data() {
        return {
            hasError: false,
            errorMessage: "",
            username: "",
            isNextButtonDisabled: false,
        };
    },
    methods: {
        handleBadWordErrorChange(error) {
            this.isNextButtonDisabled = !!error;
        },
        validateSchema() {
            this.hasError = false;
            this.errorMessage = "";
            let JoiPayLoad = {
                "User Name": this.username.trim(),
            };
            const schema = Joi.object({
                "User Name": Joi.string().pattern(/^[a-zA-Z0-9_@#$%& ]*$/).min(3).max(20).required().messages({
                    "string.base": "Username is required",
                    "string.pattern.base": "Usernames can contain letters (a-z), numbers (0-9) and underscore ."
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
        nextStep() {
            if (this.isNextButtonDisabled) return false;
            if (!this.validateSchema()) return false;
            this.hasError = false;
            this.errorMessage = "";
            usernameExist({username: this.username.trim()})
                .then((response) => {
                    if (response.status === 422) {
                        this.hasError = true;
                        this.errorMessage = response.message;
                        return;
                    }
                    if (response.data.status) {
                        this.hasError = true;
                        this.errorMessage = "Sorry. This option is already taken. Please try again.";
                        return;
                    }
                    this.hasError = false;
                    this.errorMessage = "";
                    this.$emit("nextStep", JSON.stringify({username: this.username.trim()}));
                })
                .catch((error) => {
                    toastr.error(error);
                });
        },
    },
};
</script>

<style scoped></style>