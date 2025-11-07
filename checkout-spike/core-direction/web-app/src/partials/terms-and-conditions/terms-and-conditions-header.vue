<template>
  <div class="legal-content-header">
    <div class="legal-content-header-outer-box flex items-center justify-between">
      <button class="btn-sidebar-toggle rounded-full">
        <toggle-icon />
      </button>
      <div class="legal-content-header-inner-box px-3">
        <p class="legal-content-header-title">
          Terms &amp; Conditions
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import ToggleIcon from "../../svgs/terms/toggle-icon";
export default {
    name: "TermsAndConditionsHeader",
    components: {ToggleIcon}
};
</script>

<style scoped>

</style>