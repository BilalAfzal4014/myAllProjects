<template>
  <div class="select-date-range-header flex items-center justify-between">
    <div class="title-box">
      <h4 class="select-date-range-title capitalize mr-3">
        Select Date Range
      </h4>
      <p class="select-date-range-desc">
        <info-icon-blue />
        You can select upto 7 days
      </p>
    </div>
  </div> <!-- From, between, from-other-month,to -->
</template>

<script>
import InfoIconBlue from "../../svgs/info-icon-blue";
export default {
    name: "DateRangePickerHeader",
    components: {InfoIconBlue}
};
</script>

<style>


</style>