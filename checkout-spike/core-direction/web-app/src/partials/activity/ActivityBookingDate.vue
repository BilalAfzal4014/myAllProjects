<template>
  <div class="section-header flex items-center justify-between">
    <div class="title-box">
      <h4 class="activity-listing-title capitalize">
        {{
          activity.startDate.split(',')[0]
        }},&nbsp;{{ activity.startDate.split(',')[1] }}
      </h4>
    </div>
    <div class="shown-result-box">
      <div class="capitalize">
        {{ activity.list.length }} Results
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "ActivityBookingDate",
    props: {
        activity: {
            type: Object,
            required: true
        },
    }
};
</script>

<style scoped>

</style>