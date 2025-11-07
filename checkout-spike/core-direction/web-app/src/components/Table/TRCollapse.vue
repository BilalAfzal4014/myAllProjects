<template>
  <div class="small-screen-accordion-box">
    <div class="small-screen-accordion-icon-box">
      <CircleDownArrow @click="showCollapse" />
    </div>
    <p class="small-screen-accordion-title">
      <slot />
    </p>
  </div>
</template>

<script>
import CircleDownArrow from "../../svgs/wallet/circle-down-arrow";

export default {
  name: "TRCollapse",
  components: {
    CircleDownArrow,
  },
  methods: {
    showCollapse() {
      this.$emit("onCollapseShow");
    }
  }
};
</script>