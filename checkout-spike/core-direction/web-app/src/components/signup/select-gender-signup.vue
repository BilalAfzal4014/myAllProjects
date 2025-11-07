<template>
  <div class="form-fields-box">
    <div class="field-box">
      <label class="field-label" for="gender">Your
        <strong>preferred gender</strong> selection?*
      </label>
      <p class="sub-label">
        We will use this to enhance and personalise your experience.
      </p>
      <select id="gender" v-model="gender" class="input-field">
        <option value="Male">
          Male
        </option>
        <option value="Female">
          Female
        </option>
        <option value="Unlisted">
          Unlisted
        </option>
      </select>
    </div>

    <NextInput :is-hide-press-enter="false" @nextStep="nextStep" />
  </div>
</template>

<script>
import NextInput from "@/components/signup/next-input";

export default {
    name: "SelectGenderSignup",
    components: {NextInput},
    data() {
        return {
            gender: "Male"
        };
    },
    methods: {
        nextStep() {
            this.$emit("nextStep", JSON.stringify({gender: this.gender}));
        }
    }
};
</script>

<style scoped>

</style>