<template>
  <div>
    <!-- Terms & Conditions -->
    <section id="legal">
      <div class="custom-container">
        <div class="legal-outer-box">
          <TermsAndConditionsSidebar />
          <div class="legal-inner-box">
            <div class="legal-content-box">
              <TermsAndConditionsHeader />
              <TermsAndConditionsBody />
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>

import TermsAndConditionsSidebar from "../partials/terms-and-conditions/terms-and-conditions-sidebar";
import TermsAndConditionsHeader from "../partials/terms-and-conditions/terms-and-conditions-header";
import TermsAndConditionsBody from "../partials/terms-and-conditions/terms-and-conditions-body";
import {updateMetaInformation} from "../utils";

export default {
    name: "TermsCondition",
    components: {
        TermsAndConditionsBody,
        TermsAndConditionsHeader,
        TermsAndConditionsSidebar
    },
    mounted() {
        updateMetaInformation("Terms & Conditions | Core Direction", "Core Direction, Coredirection,terms & conditions", "Read the Core Direction Terms & Conditions here.");
    }
};
</script>

<style scoped>

</style>