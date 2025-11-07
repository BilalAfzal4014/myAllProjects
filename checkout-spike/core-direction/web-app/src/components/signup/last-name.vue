<template>
  <div class="form-fields-box">
    <custom-input v-model.trim="lastname"
                  class-names="input-field"
                  input-id="lname"
                  placeholder="Last Name"
                  type="text"
                  @nextStep="nextStep"
    >
      <template #label>
        <label class="field-label" for="lName">And your
          <strong>Last name</strong>
          <span>(Optional)</span>
        </label>
      </template>
    </custom-input>
    <NextInput @nextStep="nextStep" />
  </div>
</template>

<script>
import NextInput from "@/components/signup/next-input";
import CustomInput from "@/components/form/custom-input";

export default {
    name: "LastNameSignup",
    components: {CustomInput, NextInput},
    data() {
      return {
        lastname: "",
      };
    },
    methods: {
      nextStep() {
        this.$emit("nextStep", JSON.stringify({lastname: this.lastname}));
      },
    },
};
</script>

<style scoped>
input {
  text-transform: capitalize;
}
</style>