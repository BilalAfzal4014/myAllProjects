<template>
  <div class="form-fields-box">
    <div class="field-box">
      <label class="field-label" for="dob">When were you
        <strong>born</strong>
        <span>?*</span>
      </label>
      <p class="sub-label">
        Give us the opportunity to surprise you on your special day!
      </p>
      <div class="calendar-input flex">
        <input
          id="dob"
          ref="inputDate"
          v-model="birthday"
          :max="max"
          class="input-field"
          placeholder="00 / 00 / 0000"
          type="date"
          @keyup.enter="nextStep"
        >
      </div>

      <p v-if="hasError" class="error-message flex items-center">
        <svg
          fill="none"
          height="14"
          viewBox="0 0 14 14"
          width="14"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M7.00176 14C5.61703 14.0003 4.26331 13.59 3.11184 12.8208C1.96036 12.0517 1.06288 10.9583 0.53293 9.67899C0.00297627 8.39968 -0.135641 6.99193 0.134614 5.63383C0.404869 4.27572 1.07185 3.02829 2.05119 2.04932C3.03053 1.07035 4.27822 0.403839 5.63642 0.134098C6.99463 -0.135644 8.40232 0.00350625 9.68143 0.533944C10.9605 1.06438 12.0536 1.96227 12.8223 3.11404C13.591 4.2658 14.0009 5.61968 14 7.00441C13.9988 8.85986 13.2611 10.6389 11.9488 11.9507C10.6366 13.2624 8.85721 13.9995 7.00176 14ZM7.87334 8.31972C7.87334 7.88834 7.87334 7.45696 7.87334 7.02647C7.87334 6.50422 7.49313 6.12313 6.99118 6.12754C6.87511 6.12758 6.76025 6.15101 6.65345 6.19644C6.54664 6.24187 6.45009 6.30835 6.36956 6.39193C6.28903 6.47551 6.22617 6.57446 6.18474 6.68288C6.1433 6.7913 6.12415 6.90695 6.12842 7.02294C6.12489 7.87805 6.12489 8.73258 6.12842 9.58652C6.12842 10.107 6.50951 10.5022 6.99824 10.504C7.48696 10.5057 7.86893 10.1088 7.87246 9.59004C7.87599 9.1666 7.87334 8.74316 7.87334 8.31972ZM6.99735 5.24449C7.11099 5.246 7.22381 5.22507 7.32934 5.1829C7.43488 5.14073 7.53105 5.07814 7.61235 4.99873C7.69365 4.91932 7.75847 4.82464 7.80311 4.72012C7.84774 4.61561 7.87131 4.50331 7.87246 4.38967C7.87317 4.15828 7.78192 3.93608 7.6188 3.77197C7.45567 3.60785 7.23404 3.51526 7.00265 3.51456C6.77125 3.51386 6.54906 3.6051 6.38495 3.76822C6.22083 3.93135 6.12824 4.15298 6.12754 4.38437C6.128 4.49801 6.15088 4.61045 6.19488 4.71522C6.23887 4.82 6.30311 4.91507 6.38392 4.99497C6.46472 5.07487 6.5605 5.13805 6.66577 5.18086C6.77103 5.22368 6.88372 5.2453 6.99735 5.24449Z"
            fill="#06070E"
          />
        </svg>
        <span>{{ errorMessage }}</span>
      </p>
    </div>

    <NextInput @nextStep="nextStep" />
  </div>
</template>

<script>
import NextInput from "@/components/signup/next-input";
import Joi from "joi";
import moment from "moment";

export default {
    name: "DateOfBirthSignup",
    components: {NextInput},
    data() {
        return {
            hasError: false,
            errorMessage: "",
            birthday: "",
            max: moment().format("YYYY-MM-DD"),
        };
    },
    methods: {
        validateSchema() {
            this.hasError = false;
            this.errorMessage = "";
            let JoiPayLoad = {
                "Date of birth": this.birthday,
            };
            const schema = Joi.object({
                "Date of birth": Joi.string().required(),
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
            if (!this.validateSchema()) {
                return;
            }
            this.hasError = false;
            this.errorMessage = "";
            this.$emit("nextStep", JSON.stringify({birthday: this.birthday}));
        },
    },
};
</script>

<style scoped>
.calendar-input {
  position: relative;
  width: 100%;
  max-width: 606px;
}

.calendar-input #dob {
  padding-right: 25px;
  text-align: left;
}

.calendar-input .cursor-pointer {
  position: absolute;
  right: 2px;
  top: 8px;
}

input[type="date"] {
  position: relative;
  text-align-last: left;
  text-align: left;
}

input[type="date"]::-webkit-calendar-picker-indicator {
  background: transparent;
  bottom: 0;
  color: transparent;
  cursor: pointer;
  height: auto;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: auto;
}
</style>