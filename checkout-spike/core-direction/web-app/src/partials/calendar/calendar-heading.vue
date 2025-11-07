<template>
  <div class="selected-month flex justify-between">
    <p class="current-month">
      {{ month }}
    </p>
    <p class="current-year">
      {{ year }}
    </p>
  </div>
</template>

<script>
export default {
    props: {
        month: {
            type: String,
            default: "",
        },
        year: {
            type: Number,
            default: 0,
        },
    },
    name: "CalendarHeading"
};
</script>

<style scoped>

</style>