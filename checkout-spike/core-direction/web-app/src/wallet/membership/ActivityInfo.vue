<template>
  <div class="booking-info-box flex items-center">
    <p class="booking-title">
      {{ name }}
    </p>
    <div class="booking-more-info-box">
      <button class="booking-desc-icon-box">
        <info-icon />
      </button>
      <div class="booking-desc-box">
        <p class="booking-desc" v-html="description" />
      </div>
    </div>
  </div>
</template>

<script>
import InfoIcon from "@/svgs/company/info-icon";

export default {
  name: "ActivityInfo",
  components: {InfoIcon},
  props: {
    name: {
      type: String
    },
    description: {
      type: String
    },


  }


};
</script>
