<template>
  <div class="form-fields-box">
    <div class="field-box">
      <label class="field-label" for="phone">What
        <strong>phone number </strong>can we reach you on?
        <span>*</span>
      </label>
      <p class="sub-label">
        We will only use your contact to update you regarding your bookings.
      </p>

      <vue-tel-input id="phone" v-model="phone" :auto-format="false" maxlength="15"
                     style="width:100%"
                     @enter="nextStep"
                     @country-changed="(country) => phoneNumberCountryChanged(country, 'phoneNumberDialCode', 'phone')"
      />
      <p class="error-message flex items-center">
        <strong>Info: &nbsp;&nbsp;</strong> Phone numbers should not need to include a plus sign or a country code.
      </p>
      <p v-if="hasError"
         class="error-message flex items-center"
      >
        <validation-info-icon />
        <span>{{ errorMessage }}</span>
      </p>
    </div>
    <NextInput @nextStep="nextStep" />
  </div>
</template>

<script>
import NextInput from "@/components/signup/next-input";
import Joi from "joi";
import ValidationInfoIcon from "@/svgs/errors/validation-info-icon";

export default {
    name: "PhoneNumberSignup",
    components: {ValidationInfoIcon, NextInput},
    data() {
        return {
            hasError: false,
            errorMessage: "",
            phone: "",
            phoneNumberDialCode: "",
        };
    },
    watch: {
        "phone": function () {
            this.phone = this.phone.trim();
        }
    },
    methods: {
        validateSchema() {
            this.hasError = false;
            this.errorMessage = "";
            if (this.phone.match(/[`~%^&*+$@-_#&!.]+/)) {
                this.hasError = true;
                this.errorMessage = "Phone Number can only have numbers";
                return false;
            }
            let JoiPayLoad = {
                "Phone Number": this.phone[0] === "+" ? this.phone.split("+")[1] : this.phone,
            };
            const schema = Joi.object({
                "Phone Number": Joi.number()
                    .required()
                    .messages({
                        "string.pattern.base": "Phone Number can only have numbers",
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
        phoneNumberCountryChanged(country, key, phoneNumber) {
            this[key] = "+" + country.dialCode;
            this[phoneNumber] = "";
        },

        nextStep() {
            if (!this.validateSchema()) {
                return;
            }
            this.hasError = false;
            this.errorMessage = "";
            this.$emit("nextStep", JSON.stringify({phone: `${this.phoneNumberDialCode}${this.phone}`}));
        },
    },
};
</script>

<style scoped>
.vue-tel-input:focus-within {
    box-shadow: none;
}
</style>