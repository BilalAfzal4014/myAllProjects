<template>
  <div class="calendar-header flex items-center justify-between">
    <div class="calendar-info-box flex items-center">
      <button class="prev-month" @click="prevMonth">
        <activity-diary-previous />
      </button>
      <p class="selected-date">
        <span class="selected-month">{{ currentMonthName }}</span>
        <span class="selected-year">{{ currentYear }}</span>
      </p>
      <button class="next-month" @click="nextMonth">
        <activity-diary-next />
      </button>
    </div>
    <div class="calendar-type-box flex items-center rounded-full">
      <button
        :class="{ active: calendarType === 'weekly' }"
        class="calendar-type-weekly"
        @click="switchCalendarType('weekly')"
      >
        Weekly
      </button>
      <button
        :class="{ active: calendarType === 'monthly' }"
        class="calendar-type-monthly"
        @click="switchCalendarType('monthly')"
      >
        Monthly
      </button>
    </div>
  </div>
</template>
<script>
import ActivityDiaryPrevious from "@/svgs/activity-diary/activity-diary-previous";
import ActivityDiaryNext from "@/svgs/activity-diary/activity-diary-next";

export default {
  name: "UserActivityCalendarHeader",
  components: {ActivityDiaryNext, ActivityDiaryPrevious},
  props: {
    currentMonthName: {
      type: String,
      required: true,
    },
    currentYear: {
      type: Number,
      required: true
    },
    calendarType: {
      type: String,
      required: true
    }
  },
  methods: {
    prevMonth() {
      this.$emit("prevMonth");
    },
    nextMonth() {
      this.$emit("nextMonth");
    },
    switchCalendarType(calendarType) {
      this.$emit("switchCalendar", calendarType);
    }
  }
};
</script>