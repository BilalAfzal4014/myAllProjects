<template>
  <div>
    <p
      class="mb-4"
      style="font-family: 'Montserrat', sans-serif;font-size: 12px;font-weight: 500;line-height: 2;"
    >
      By submitting this form you agree to the
      <a
        href="https://www.dubaifitnesschallenge.com/en/event-waiver"
        target="_blank"
        style="color: #06A7E0;text-decoration: underline;"
      >Event Waiver</a>,
      <a
        href="https://www.dubaifitnesschallenge.com/terms-conditions/"
        style="color: #06A7E0;text-decoration: underline;"
        target="_blank"
      >Terms of use</a>
      and acknowledge you have read our
      <a
        href="https://www.dubaifitnesschallenge.com/privacy-policy/"
        style="color: #06A7E0;text-decoration: underline;"
        target="_blank"
      >Privacy Notice</a>.
    </p>
    <div class="checkbox-field-box mb-6">
      <div class="form-group">
        <input id="promotionalEmail" type="checkbox">
        <label for="promotionalEmail">
          I want to receive promotional emails from and about Dubai Tourism
          products and services.
        </label>
      </div>
    </div>
    <div class="checkbox-field-box mb-6">
      <div class="form-group">
        <input id="relevantPromotionalEmail" type="checkbox">
        <label for="relevantPromotionalEmail">
          I want to recieve relevant promotional email from Coredirection
          partners.
        </label>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "TermsAndConditions",
    props: {
        checked: {
            type: String,
            default: "",
        },
    },
    methods: {
    // termsConditions(event){
    //   if(event.target.checked){
    //     this.$emit("checked",true);
    //   } else {
    //     this.$emit("checked",false);
    //   }
    // }
    },
};
</script>

<style scoped></style>
