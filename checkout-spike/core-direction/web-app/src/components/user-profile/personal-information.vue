<template>
  <div class="custom-container">
    <div class="signup-form-container mx-auto">
      <div class="personal-detail-box">
        <p class="form-title">
          Update Your Personal Info
        </p>
        <form>
          <div class="mb-9">
            <div class="grid grid-cols-2 gap-4">
              <div class="form-control-box">
                <custom-input v-model="user.firstname"
                              class-names="form-control block"
                              input-id="fName"
                              placeholder="Enter your First Name"
                              type="text"
                              @badWordErrorChange="handleBadWordErrorChange"
                >
                  <template #label>
                    <label class="form-label block" for="fname">First Name<span class="field-primary">*</span></label>
                  </template>
                </custom-input>
              </div>
              <custom-input v-model="user.lastname"
                            class-names="form-control block"
                            input-id="lname"
                            placeholder="Enter your Last Name"
                            type="text"
                            @badWordErrorChange="handleBadWordErrorChange"
              >
                <template #label>
                  <label class="form-label block" for="lname">Last Name<span class="field-primary">*</span></label>
                </template>
              </custom-input>
            </div>
          </div>
          <div class="mb-9">
            <custom-input v-model="user.email"
                          :disabled="true"
                          class-names="form-control block"
                          input-id="email"
                          placeholder="johnsmith@example.com"
                          type="email"
                          @badWordErrorChange="handleBadWordErrorChange"
            >
              <template #label>
                <label class="form-label block" for="email">Enter your email address<span
                  class="field-primary"
                >*</span></label>
              </template>
              <template #icons>
                <profile-email-icon />
              </template>
            </custom-input>
          </div>
          <div class="mb-9">
            <div class="grid grid-cols-5 gap-4 sm-layout-shift">
              <div class="col-span-3">
                <label class="form-label block" for="phone">Phone Number<span class="field-primary">*</span></label>
                <vue-tel-input id="phone" v-model="user.phone" :auto-format="false"
                               style="width:100%"
                               @country-changed="(country) => phoneNumberCountryChanged(country, 'phoneNumberDialCode', 'phone')"
                />
              </div>
              <div class="col-span-2">
                <div class="field-control-box">
                  <label class="form-label block" for="gender">Gender</label>
                  <select id="gender" v-model="user.gender" class="form-control block">
                    <option :selected="user.gender === 'Male'" value="Male">
                      Male
                    </option>
                    <option :selected="user.gender === 'Female'" value="Female">
                      Female
                    </option>
                    <option :selected="user.gender === 'Unlisted'" value="Unlisted">
                      Unlisted
                    </option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="mb-9">
            <div class="field-control-box">
              <label class="form-label block" for="dob">Select your Date Of Birth</label>
              <datepicker id="date-of-birth"
                          v-model="user.birthdate"
                          :disabled-dates="{from: new Date()}"
                          format="yyyy-MM-dd"
                          placeholder="Date of Birth"
              />
              <profile-calendar />
            </div>
          </div>
        </form>
      </div>
      <div class="emergency-contact-detail-box">
        <p class="form-title">
          Emergency contact details
        </p>
        <p class="form-desc">
          <strong>Mr./Ms./Mrs. “{{ user.emergency_firstname }} {{ user.emergency_lastname }}”</strong> contact details
          have been set as your
          emergency contact. In case of any emergency, he/she will be informed about your good health.
        </p>
        <form>
          <div class="mb-9">
            <div class="grid grid-cols-2 gap-4">
              <custom-input v-model="user.emergency_firstname"
                            class-names="form-control block"
                            input-id="fName"
                            placeholder="Enter your First Name"
                            type="text"
                            @badWordErrorChange="handleBadWordErrorChange"
              >
                <template #label>
                  <label class="form-label block" for="e-fname">First Name</label>
                </template>
              </custom-input>
              <custom-input v-model="user.emergency_lastname"
                            class-names="form-control block"
                            input-id="fName"
                            placeholder="Enter your Last Name"
                            type="text"
                            @badWordErrorChange="handleBadWordErrorChange"
              >
                <template #label>
                  <label class="form-label block" for="e-lname">Last Name</label>
                </template>
              </custom-input>
            </div>
          </div>
          <div class="mb-9">
            <custom-input v-model="user.emergency_email"
                          class-names="form-control block"
                          input-id="email"
                          placeholder="johnsmith@example.com"
                          type="email"
                          @badWordErrorChange="handleBadWordErrorChange"
            >
              <template #label>
                <label class="form-label block" for="email">Enter your email address<span
                  class="field-primary"
                >*</span></label>
              </template>
              <template #icons>
                <profile-email-icon />
              </template>
            </custom-input>
          </div>
          <div class="mb-9">
            <label class="form-label block" for="e-phone">Enter your emergency phone number</label>
            <vue-tel-input id="phone" v-model="user.emergency_phone" :auto-format="false"
                           :mode="'international'"
                           style="width:100%"
                           @country-changed="(country) => phoneNumberCountryChanged(country, 'emergencyPhoneNumberDialCode', 'emergency_phone')"
            />
          </div>
        </form>
      </div>
      <action-button @updateProfile="updateProfile" />
    </div>
  </div>
</template>

<script>
import ActionButton from "@/components/user-profile/action-button";
import ProfileCalendar from "@/svgs/profile/profile-calendar";
import ProfileEmailIcon from "@/svgs/profile/profile-email-icon";
import moment from "moment";
import Joi from "joi";
import * as toastr from "toastr";
import Datepicker from "vuejs-datepicker";
import CustomInput from "@/components/form/custom-input";

export default {
  name: "PersonalInformation",
  components: {CustomInput, ProfileEmailIcon, ProfileCalendar, ActionButton, Datepicker},
  props: {
    user: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      phoneNumberDialCode: "",
      emergencyPhoneNumberDialCode: "",
      phoneNumber: "",
      emergencyPhoneNumber: "",
      isNextButtonDisabled: false,
      bindProps: {
        inputOptions: {
          showDialCode: false,

        },
      }
    };
  },
  updated() {
    this.user.phone = this.user.phone[0] === "+" ? this.user.phone.split("+")[1] : this.user.phone;
    this.user.emergency_phone = this.user.emergency_phone[0] === "+" ? this.user.emergency_phone.split("+")[1] : this.user.emergency_phone;
  },

  watch: {
    "user.email": function () {
      this.user.email = this.user.email.trim();
    },
    "user.emergency_email": function () {
      this.user.emergency_email = this.user.emergency_email.trim();
    },
    "user.phone_number": function () {
      this.user.phone_number = this.user.phone_number.trim();
    },
    "user.emergency_phone": function () {
      this.user.emergency_phone = this.user.emergency_phone.trim();
    },
    "user.birthdate": function () {
      this.user.birthdate = moment(this.user.birthdate).format("YYYY-MM-DD");
    }
  },
  methods: {
    handleBadWordErrorChange(error) {
      this.isNextButtonDisabled = !!error;
    },
    phoneNumberCountryChanged(country, key, phoneNumber) {
      this[key] = "+" + country.dialCode;
      this.user[phoneNumber] = this.user[phoneNumber][0] === "+" ? this.user[phoneNumber].split("+")[1] : this.user[phoneNumber];
    },
    getCompleteNumber(dialCode, phoneNumber) {
      if (phoneNumber.includes(dialCode) || phoneNumber[0] === "+") {
        return phoneNumber;
      } else if (phoneNumber.includes(dialCode.split("+")[1])) {
        return phoneNumber;
      }
      return dialCode + phoneNumber;
    },
    updateProfile() {
      if (!this.validateSchema()) {
        return;
      }
      this.user.phone = this.getCompleteNumber(this.phoneNumberDialCode, this.user.phone);
      this.user.emergency_phone = this.getCompleteNumber(this.emergencyPhoneNumberDialCode, this.user.emergency_phone);
      this.user.birthdate = moment(this.user.birthdate).format("YYYY-MM-DD");
      this.$emit("updateProfile", JSON.stringify(this.user));
    },
    validateSchema() {
      let JoiPayLoad = {
        "First Name": this.user.firstname.trim(),
        "Last Name": this.user.lastname.trim(),
        "Email": this.user.email,
        "Phone Number": this.user.phone[0] === "+" ? this.user.phone.split("+")[1] : this.user.phone,
        "Gender": this.user.gender,
        "Date Of Birth": moment(this.user.birthdate).format("YYYY-MM-DD"),
        "Emergency First Name": this.user.emergency_firstname.trim(),
        "Emergency Last Name": this.user.emergency_lastname.trim(),
        "Emergency Email": this.user.emergency_email,
        "Emergency Phone Number": this.user.emergency_phone[0] === "+" ? this.user.emergency_phone.split("+")[1] : this.user.emergency_phone
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
        "Email": Joi.string().email({tlds: {allow: false}}).required(),
        "Date Of Birth": Joi.date().required(),
        "Phone Number": Joi.string().min(8)
          .max(15).pattern(/^[0-9]+$/).required()
          .messages({
            "string.pattern.base": "Phone Number can only have numbers"
          }),
        "Gender": Joi.string().required()
          .messages({
            "any.only": "Please select Gender"
          }),
        is_vaccinated: Joi.boolean().invalid(false)
          .messages({
            "any.invalid": "Please tick on Covid declaration.",
          }),
      }).unknown(true);

      const {error} = schema.validate(JoiPayLoad);

      if (error) {
        toastr.error(error);
        return false;
      }
      if (this.isNextButtonDisabled) return false;
      return true;
    },
  }
};
</script>

<style scoped>
input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-calendar-picker-indicator {
  display: none;
  -webkit-appearance: none;

}

.vue-tel-input:focus-within {
  box-shadow: none;
}

</style>
