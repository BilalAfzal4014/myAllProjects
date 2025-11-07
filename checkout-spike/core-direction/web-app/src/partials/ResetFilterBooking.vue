<template>
  <div class="reset-btn-box lg:mb-0 mb-4 lg:mr-4 ml-2">
    <button class=" rounded-full" @click="resetFilter">
      Reset Filter
    </button>
  </div>
</template>

<script>
import emitter from "tiny-emitter/instance";
export default {
    name: "ResetFilterBooking",
    methods:{
        resetFilter: function(){
            this.$emit("reset","reset");
            emitter.emit("reset_filter", "reset_filter");
        }
    }
};
</script>

<style scoped>
@media screen and (max-width: 767px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .reset-btn-box{
    margin-left: 0 !important;
  }
}
</style>