<template>
  <div class="business-logo-box">
    <img :src="logoUrl" alt="" class="rounded-full" height="27px" width="27px">
  </div>
</template>

<script>
export default {
  name: "BusinessLogo",
  props: {
    logo: {
      type: String
    }
  },
  computed: {
    logoUrl() {
      return this.logo ? this.constants.getImageUrl("member/" + this.logo) : require("@/assets/images/subscription.webp");
    }
  }

};
</script>
