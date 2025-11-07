<template>
  <div class="calendar-body">
    <div class="calendar-row">
      <div
        v-for="day in days"
        :key="day"
        :class="getColumnClass(day)"
      >
        {{ day }}
      </div>
    </div>
    <div class="calendar-row">
      <div
        v-for="day in pastMonthDays"
        :key="'past-month-' + day"
        class="calendar-column"
      >
        <div class="date-cell">
          &nbsp;
        </div>
      </div>
      <div
        v-for="(num, key, index) in currentDayEvents"
        :key="'currentDays-' + index"
        class="calendar-column"
      >
        <div class="date-cell flex">
          {{ key | extractDay }}
          <span @click="showActivityModal(num, key)">
            <svg
              v-if="num.length"
              fill="none"
              height="27"
              viewBox="0 0 24 27"
              width="24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M11.5112 0.00920636C12.734 2.12717 13.8634 4.08346 14.9929 6.03975C16.2811 8.27089 17.553 10.5114 18.8829 12.74C19.1256 13.1604 19.1617 13.4844 18.9044 13.9348C16.5305 18 14.1659 22.0813 11.8106 26.1789C11.7578 26.274 11.6633 26.3717 11.4998 26.5739C10.333 24.5529 9.20352 22.5966 8.08339 20.6565C7.85936 20.2685 8.13605 20.001 8.29455 19.7154C9.39292 17.8094 10.482 15.8873 11.6058 13.9881C11.8793 13.5284 11.8662 13.1695 11.6048 12.7168C10.4685 10.786 9.36455 8.83659 8.22825 6.9058C7.95755 6.43694 7.95371 6.09425 8.22722 5.63454C9.3076 3.84672 10.3506 1.99422 11.5112 0.00920636Z"
                fill="#690FAD"
              />
            </svg>
          </span>
        </div>
        <div
          v-if="num.length === 1"
          class="booking-detail-popup cursor-pointer"
          @click="showActivityDetail(num)"
        >
          <p class="booking-detail-title uppercase">
            {{ getActivityProperty(num[0], 'name') }}
          </p>
          <p class="booking-detail-location">
            {{ getActivityProperty(num[0], 'location') }}
          </p>
          <p class="booking-detail-time">
            {{
              num[0]?.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY
                  ? convertIS8601ToTime(num[0]?.start_time)
                  : num[0]?.startTime
            }}
            -
            {{
              num[0]?.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY
                  ? convertIS8601ToTime(num[0]?.end_time)
                  : num[0]?.endTime
            }}
          </p>
        </div>
        <div
          v-if="num.length > 1"
          class="booking-detail-popup multiple-booking multiple-detail-booking-modal cursor-pointer"
          @click="showEventDetailModal(num, key)"
        >
          <p
            class="booking-detail-title uppercase multiple-detail-booking-modal"
          >
            Multiple Bookings
          </p>
          <div class="flex items-center multiple-detail-booking-modal">
            <p class="multiple-detail-booking-modal">
              Click to see
            </p>
            <activity-diary-detail-icon />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {extractDay} from "@/filters/DateFilter";
import {ACTIVITY_TYPES} from "@/common/constants/constants";
import generalMixin from "@/mixin/generalMixin";
import ActivityDiaryDetailIcon from "@/svgs/activity-diary/activity-diary-detail-icon";

export default {
  name: "UserActivityCalendarBody",
  components: {ActivityDiaryDetailIcon},
  mixins: [generalMixin],
  props: {
    currentDayEvents: {
      type: [Array, Object],
      required: true
    },
    days: {
      type: Array,
      required: true
    },
    pastMonthDays: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      ACTIVITY_TYPES
    };
  },
  filters: {
    extractDay
  },

  methods: {
    getColumnClass(day) {
      return {
        "calendar-column": true,
        "border-left-calendar": this.isFirstDay(day)
      };
    },
    isFirstDay(day) {
      return this.days.indexOf(day) === 0;
    },
    getActivityProperty(activity, property) {
      if (!activity) return "";
      switch (property) {
      case "name":
        return activity.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY
          ? activity.activity_name
          : activity.activityName;

      case "location":
        return activity.type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY
          ? activity.activity_type?.name
          : activity.actt_name;

      default:
        return "";
      }
    },
    showActivityModal(event) {
      this.$emit("showActivityModal", event);
    },
    showActivityDetail(detail) {
      this.$emit("showActivityDetail", detail);
    },
    showEventDetailModal(details) {
      this.$emit("showEventDetailModal", details);
    }
  },

};
</script>

<style scoped>

</style>