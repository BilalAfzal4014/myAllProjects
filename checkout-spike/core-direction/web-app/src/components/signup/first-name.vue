<template>
  <div class="form-fields-box">
    <custom-input v-model.trim="firstname"
                  class-names="input-field"
                  input-id="fName"
                  placeholder="First Name"
                  type="text"
                  @badWordErrorChange="handleBadWordErrorChange"
                  @nextStep="nextStep"
    >
      <template #label>
        <label class="field-label" for="fName">Let’s start with your
          <strong>First name.</strong>
          <span>*</span>
        </label>
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
import CustomInput from "@/components/form/custom-input";
import ValidationInfoIcon from "@/svgs/errors/validation-info-icon";

export default {
    name: "FirstNameSignup",
    components: {ValidationInfoIcon, CustomInput, NextInput},
    data() {
        return {
            hasError: false,
            errorMessage: "",
            firstname: "",
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
                "First Name": this.firstname,
            };
            const schema = Joi.object({
                "First Name": Joi.string()
                    .min(1)
                    .max(30)
                    .required()
                    .pattern(/^[a-zA-Z_ ]*$/)
                    .messages({
                        "string.base": "First Name is required",
                        "string.pattern.base": "First Name can only contains alphabet."
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
            this.$emit("nextStep", JSON.stringify({firstname: this.firstname}));
        },
    },
};
</script>

<style scoped>
input {
  text-transform: capitalize;
}
</style>