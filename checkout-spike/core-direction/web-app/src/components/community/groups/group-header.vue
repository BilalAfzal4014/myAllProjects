<template>
  <div class="section-header flex items-center justify-between">
    <div class="title-box">
      <h4 class="section-title capitalize">
        Groups
      </h4>
    </div>
    <div class="see-all-btn-box">
      <a class="flex items-center capitalize text-vibrantDark btn-see-all" href="/group-filter">Explore All
        <blue-chevron-right />
      </a>
    </div>
  </div>
</template>

<script>
import BlueChevronRight from "@/svgs/arrows/blue-chevron-right";

export default {
    name: "GroupHeader",
    components: {BlueChevronRight}
};
</script>

<style scoped>

</style>