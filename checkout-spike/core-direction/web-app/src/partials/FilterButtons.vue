<template>
  <div class="advance-filter-btn-box flex items-center justify-center flex-wrap">
    <button class="btn-filter-reset rounded-full" @click="resetFilter">
      Reset Filters
    </button>
    <button class="btn-apply-filter rounded-full" @click="applyFilter">
      Apply Filters
    </button>
  </div>
</template>

<script>
export default {
    name: "FilterButtons",
    methods:{
        resetFilter(){
            this.$emit("reset","reset filter");
        },
        applyFilter(){
            this.$emit("apply","apply Filter");
        }
    }
};
</script>

<style scoped>

</style>