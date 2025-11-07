<template>
  <diary-calendar :username="username" />
</template>

<script>
import DiaryCalendar from "@/partials/activity-diary-calendar/diary-calendar";

export default {
    name: "CalendarActivityListing",
    components: {DiaryCalendar},
    props: {
        username: {
            type: String,
            default: null
        }
    }
};
</script>

<style scoped>

</style>