<template>
  <section class="flex days-name">
    <p
      v-for="day in dayNames"
      :key="'previous-'+day"
      class="text-center"
      style="width: 14.28%;"
    >
      {{ day }}
    </p>
  </section>
</template>

<script>
import {DAY_NAMES} from "@/components/calendar/calendar-constants";

export default {
    name: "CalendarDayNames",
    data() {
        return {
            dayNames: DAY_NAMES
        };
    }
};
</script>

<style scoped>

</style>