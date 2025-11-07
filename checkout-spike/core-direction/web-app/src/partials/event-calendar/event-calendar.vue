<template>
  <section id="calendar">
    <social-sharing-on-signup :show-modal="isShowSharingModal" @onCloseSharingModal="closeSharingModal" />
    <cancel-booking-modal v-if="isCancelModalShow" :activity-detail="event" @close="closeCancelBookingModal"
                          @onCancelledBooking="$emit('refetchUpcomingBooking')"
    />
    <calendar-modal :activity-detail="event" :is-modal-show-prop="isModalShow" @cancelBooking="cancelBooking"
                    @onCancelBooking="$emit('onBookingCancelled')"
                    @onHideCalendarModal="hideCalendarModal" @onShareActivityWithYourFriends="shareActivity"
    />
    <calendar-detail-modal :date-and-day="dateAndDay" :is-show-detail-modal-prop="isShowDetailModal"
                           :multiple-events="eventLists" @hideDetailModal="hideDetailModal"
                           @showDetailModal="showActivityDetailModal"
    />
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            Calendar
          </h4>
        </div>
      </div>
      <div class="calendar-header flex items-center justify-between">
        <div class="calendar-info-box flex items-center">
          <button class="prev-month" @click="prev">
            <svg fill="none" height="24" viewBox="0 0 14 24" width="14" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M0.805661 12.6585L11.8879 23.7408C12.2548 24.0951 12.8394 24.0849 13.1938 23.718C13.5394 23.3601 13.5394 22.7928 13.1938 22.4349L2.76443 12.0056L13.1938 1.57627C13.5543 1.21566 13.5543 0.631029 13.1938 0.270424C12.8331 -0.090128 12.2485 -0.0901281 11.8879 0.270424L0.805661 11.3527C0.445109 11.7133 0.445109 12.2979 0.805661 12.6585Z"
                fill="black"
              />
            </svg>
          </button>
          <p class="selected-date">
            <span class="selected-month">{{ currentMonthName }}</span>
            <span class="selected-year">{{ currentYear }}</span>
          </p>
          <button class="next-month" @click="next">
            <svg fill="none" height="24" viewBox="0 0 14 24" width="14" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M13.1943 11.3415L2.11209 0.259231C1.7452 -0.0950976 1.16057 -0.0849244 0.806242 0.281959C0.460571 0.639859 0.460571 1.20723 0.806242 1.56508L11.2356 11.9944L0.806246 22.4237C0.445694 22.7843 0.445694 23.369 0.806246 23.7296C1.16691 24.0901 1.75148 24.0901 2.11209 23.7296L13.1943 12.6473C13.5549 12.2867 13.5549 11.7021 13.1943 11.3415Z"
                fill="black"
              />
            </svg>
          </button>
        </div>
        <div class="calendar-type-box flex items-center rounded-full">
          <button :class="`calendar-type-weekly ${calendarType === 'weekly' ? 'active':''}`"
                  @click="switchCalendar(calendarTypes.WEEKLY)"
          >
            Weekly
          </button>
          <button :class="`calendar-type-monthly ${calendarType === 'monthly' ? 'active':''}`"
                  @click="switchCalendar(calendarTypes.MONTHLY)"
          >
            Monthly
          </button>
        </div>
      </div>
      <div :class="`calendar-body ${calendarType === calendarTypes.MONTHLY ? 'month-view': 'week-view'}`">
        <div class="calendar-row">
          <div v-for="(day,index) in days" :key="index"
               :class="`calendar-column ${index === 0 ? 'border-left-calendar':''}`"
          >
            {{ day }}
          </div>
        </div>
        <div class="calendar-row">
          <div v-for="num in dayStart" :key="'remainingDays-'+num" :class="`calendar-column`">
            <span />
          </div>
          <div v-for="(num,key,index) in getDayEvent()" :key="'currentDays-'+index" class="calendar-column">
            <div class="date-cell flex">
              {{ key | extractDay }}
              <span @click="showActivityModal(num,key)">
                <svg v-if="num.length" fill="none" height="27" viewBox="0 0 24 27" width="24"
                     xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M11.5112 0.00920636C12.734 2.12717 13.8634 4.08346 14.9929 6.03975C16.2811 8.27089 17.553 10.5114 18.8829 12.74C19.1256 13.1604 19.1617 13.4844 18.9044 13.9348C16.5305 18 14.1659 22.0813 11.8106 26.1789C11.7578 26.274 11.6633 26.3717 11.4998 26.5739C10.333 24.5529 9.20352 22.5966 8.08339 20.6565C7.85936 20.2685 8.13605 20.001 8.29455 19.7154C9.39292 17.8094 10.482 15.8873 11.6058 13.9881C11.8793 13.5284 11.8662 13.1695 11.6048 12.7168C10.4685 10.786 9.36455 8.83659 8.22825 6.9058C7.95755 6.43694 7.95371 6.09425 8.22722 5.63454C9.3076 3.84672 10.3506 1.99422 11.5112 0.00920636Z"
                    fill="#690FAD"
                  />
                </svg>
              </span>
            </div>
            <div v-if="num.length === 1" class="booking-detail-popup cursor-pointer" @click="showActivityDetail(num)">
              <p class="booking-detail-title uppercase">
                {{ num[0].activityName }}
              </p>
              <p class="booking-detail-location">
                {{ num[0].city }}
              </p>
              <p class="booking-detail-time">
                {{ num[0].startTime }} - {{ num[0].endTime }}
              </p>
            </div>
            <div v-if="num.length > 1"
                 class="booking-detail-popup multiple-booking multiple-detail-booking-modal cursor-pointer"
                 @click="showEventDetailModal(num,key)"
            >
              <p class="booking-detail-title uppercase multiple-detail-booking-modal">
                Multiple Bookings
              </p>
              <div class="flex items-center multiple-detail-booking-modal">
                <p class="multiple-detail-booking-modal">
                  Click to see
                </p>
                <svg fill="none" height="27" viewBox="0 0 24 27" width="24" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M11.5112 0.00920636C12.734 2.12717 13.8634 4.08346 14.9929 6.03975C16.2811 8.27089 17.553 10.5114 18.8829 12.74C19.1256 13.1604 19.1617 13.4844 18.9044 13.9348C16.5305 18 14.1659 22.0813 11.8106 26.1789C11.7578 26.274 11.6633 26.3717 11.4998 26.5739C10.333 24.5529 9.20352 22.5966 8.08339 20.6565C7.85936 20.2685 8.13605 20.001 8.29455 19.7154C9.39292 17.8094 10.482 15.8873 11.6058 13.9881C11.8793 13.5284 11.8662 13.1695 11.6048 12.7168C10.4685 10.786 9.36455 8.83659 8.22825 6.9058C7.95755 6.43694 7.95371 6.09425 8.22722 5.63454C9.3076 3.84672 10.3506 1.99422 11.5112 0.00920636Z"
                    fill="#690FAD"
                  />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import moment from "moment";
import CalendarModal from "@/partials/calendar/calendar-modal";
import CalendarDetailModal from "@/partials/event-calendar/calendar-detail-modal";
import CancelBookingModal from "@/partials/event-calendar/cancel-booking-modal";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import Emitter from "tiny-emitter/instance";

export default {
  name: "EventCalendar",
  components: {SocialSharingOnSignup, CancelBookingModal, CalendarDetailModal, CalendarModal},
  props: {
    events: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      isShowSharingModal: false,
      currentDate: new Date().getUTCDate(),
      currentMonth: new Date().getMonth(),
      currentYear: new Date().getFullYear(),
      days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      calendarType: "monthly",
      isModalShow: false,
      calendarTypes: {
        WEEKLY: "weekly",
        MONTHLY: "monthly"
      },
      eventLists: [],
      event: {},
      dateAndDay: {
        day: "",
        date: "",
      },
      isShowDetailModal: false,
      isCancelModalShow: false,
      allEventLists: {},
      dayStart: null,
    };
  },
  filters: {
    extractDay: function (value) {
      return moment(value, "DD-MM-YYYY").format("DD");
    }
  },
  methods: {
    shareActivity() {
      this.isShowSharingModal = true;
      Emitter.emit(
        "social_sharing_modal",
        this.event.facilityId,
        this.event.latitude,
        this.event.longitude,
        "booking"
      );
      this.isModalShow = false;
    },
    closeSharingModal() {
      this.isShowSharingModal = false;
    },
    cancelBooking() {
      this.isModalShow = false;
      this.isCancelModalShow = true;
    },
    closeCancelBookingModal() {
      this.isCancelModalShow = false;
    },
    hideDetailModal() {
      this.isShowDetailModal = false;
    },
    showActivityDetailModal(event) {
      this.event = event;
      this.isShowDetailModal = false;
      this.isModalShow = true;
    },
    showActivityModal(event, date) {
      event.length === 1 ? this.showActivityDetail(event) : this.showEventDetailModal(event, date);
    },
    showEventDetailModal(events, date) {
      this.isShowDetailModal = true;
      this.eventLists = events;
      this.dateAndDay.day = this.getDayName(date);
      this.dateAndDay.date = date;
    },
    showActivityDetail(event) {
      this.event = event[0];
      this.isModalShow = true;
    },
    hideCalendarModal() {
      this.isModalShow = false;
    },
    daysInMonth() {
      return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
    },
    startDay() {
      return new Date(this.currentYear, this.currentMonth, 1).getDay();
    },
    currentMonthDoubleDigit() {
      return String(this.currentMonth + 1).padStart(2, "0");
    },
    currentDateDoubleDigit(date) {
      return String(date).padStart(2, "0");
    },
    next() {
      if (this.currentMonth === 11) {
        this.currentMonth = 0;
        this.currentYear++;
      } else {
        this.currentMonth++;
      }
    },
    switchCalendar(type) {
      if (type === this.calendarTypes.WEEKLY) {
        this.calendarType = this.calendarTypes.WEEKLY;
      } else {
        this.calendarType = this.calendarTypes.MONTHLY;
      }
    },
    getDayName(date) {
      return moment(date, "DD-MM-YYYY").format("dddd");
    },
    prev() {
      if (this.currentMonth === 0) {
        this.currentMonth = 11;
        this.currentYear--;
      } else {
        this.currentMonth--;
      }
    },
    appendMonthAndYear(day) {
      return `${this.currentDateDoubleDigit(day)}-${this.currentMonthDoubleDigit()}-${this.currentYear}`;
    },
    getDayEvent() {
      let eventDateObject = {};
      const {startDay, endDay} = this.firstWeekRemainingDays();
      for (let day = startDay; day <= endDay; day++) {
        eventDateObject[this.appendMonthAndYear(day)] = [];
      }
      if (this.events) {
        this.events.forEach((event) => {
          if (eventDateObject[event.class_date]) {
            eventDateObject[event.class_date].push(event);
          }
        });
      }
      return eventDateObject;
    },
    firstWeekRemainingDays() {
      if (this.calendarType === this.calendarTypes.WEEKLY) {
        if (this.getWeekNumber() === 0) {
          const totalDaysInOneWeek = 7;
          this.dayStart = new Date(this.currentYear, this.currentMonth, 1).getDay();
          return {"startDay": 1, "endDay": totalDaysInOneWeek - this.dayStart};

        }
        if (this.getCurrentMonth !== this.currentMonth) {
          const totalDaysInOneWeek = 7;
          this.dayStart = new Date(this.currentYear, this.currentMonth, 1).getDay();
          return {"startDay": 1, "endDay": totalDaysInOneWeek - this.dayStart};
        }
        this.dayStart = 0;
        return this.getStartAndEndDayOfWeek;
      }
      this.dayStart = new Date(this.currentYear, this.currentMonth, 1).getDay();
      return this.getNumberOfDaysInMonth;
    },
    getWeekNumber() {
      const currentDate = new Date();
      const date = currentDate.getDate();
      const day = currentDate.getDay();
      return Math.ceil((date - 1 - day) / 7);
    },
  },
  computed: {
    currentMonthName() {
      return new Date(
        this.currentYear,
        this.currentMonth
      ).toLocaleString("default", {month: "long"});
    },

    getNumberOfDaysInMonth() {
      return {"startDay": 1, "endDay": this.daysInMonth()};
    },
    getStartAndEndDayOfWeek() {
      const today = moment();
      const from_date = today.startOf("week").format("DD");
      const to_date = today.endOf("week").format("DD");
      return {"startDay": from_date, "endDay": to_date};
    },


    getCurrentMonth() {
      return new Date().getMonth();
    }
  },
};
</script>

<style scoped>
#calendar .calendar-header {
  margin-top: 22px;
  margin-bottom: 22px;
}

#calendar .calendar-info-box .selected-date {
  font-family: 'Montserrat', sans-serif;
  font-size: 20px;
  line-height: 24.38px;
  font-weight: 400;
  margin-left: 16px;
  margin-right: 16px;
  color: #690FAD;
}

@media screen and (max-width: 991px) {
  #calendar .calendar-info-box .selected-date {
    font-size: 14px;
    line-height: 21.94px;
  }
}

@media screen and (max-width: 413px) {
  #calendar .calendar-info-box .selected-date {
    font-size: 12px;
    margin-left: 5px;
    margin-right: 5px;
  }

  .calendar-info-box .prev-month svg,
  .calendar-info-box .next-month svg {
    max-height: 16px;
  }
}

#calendar .calendar-info-box .selected-date .selected-month {
  margin-right: 12px;
}

@media screen and (max-width: 374px) {
  #calendar .calendar-info-box .selected-date .selected-month {
    margin-right: 4px;
  }

  #calendar .calendar-info-box .selected-date {
    margin-left: 3px;
    margin-right: 3px;
  }
}

#calendar .calendar-info-box svg,
#calendar .calendar-info-box path {
  fill: #690FAD;
}

#calendar .calendar-type-box {
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
}

#calendar .calendar-type-box .calendar-type-weekly,
#calendar .calendar-type-box .calendar-type-monthly {
  color: #000000;
  background-color: #FFFFFF;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  line-height: 12.63px;
  font-weight: 700;
  padding: 10px 25px;
}

@media screen and (max-width: 767px) {
  #calendar .calendar-type-box .calendar-type-weekly,
  #calendar .calendar-type-box .calendar-type-monthly {
    padding: 10px 21px;
  }
}

@media screen and (max-width: 374px) {
  #calendar .calendar-type-box .calendar-type-weekly,
  #calendar .calendar-type-box .calendar-type-monthly {
    padding: 8px 10px;
  }
}

#calendar .calendar-type-box .calendar-type-weekly:hover,
#calendar .calendar-type-box .calendar-type-monthly:hover {
  color: #FFFFFF;
  background-color: #690FAD;
}

#calendar .calendar-type-box .calendar-type-weekly {
  border-radius: 30px 0 0 30px;
}

#calendar .calendar-type-box .calendar-type-monthly {
  border-radius: 0 30px 30px 0;
}

.calendar-column:nth-child(7n-6) {
  border-left: 1px solid #CAA8F5 !important;
}

#calendar .calendar-type-box .active {
  color: #FFFFFF;
  background-color: #690FAD;
}

#calendar .calendar-body .calendar-row {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
}

#calendar .calendar-body .calendar-row:first-child {
  min-height: 46px;
  max-height: 46px;
}

#calendar .calendar-body .calendar-row:first-child .calendar-column {
  padding: 8px 15px;
  min-height: 46px;
  max-height: 46px;
  font-weight: 600;
  color: #690FAD;
}

@media screen and (max-width: 767px) {
  #calendar .calendar-body .calendar-row:first-child .calendar-column {
    padding: 6px;
  }
}

#calendar .calendar-body .calendar-column {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  font-weight: 400;
  line-height: 13.41px;
  border-right: 1px solid #CAA8F5;
  padding: 17px 5px 5px 21px;
  min-height: 69px;
  max-height: 69px;
  border-bottom: 1px solid #36363626 !important;
}

@media screen and (max-width: 991px) {
  #calendar .calendar-body .calendar-column {
    position: relative;
  }
}

@media screen and (max-width: 767px) {
  #calendar .calendar-body .calendar-column {
    padding-top: 14px;
    padding-left: 6px;
  }
}

@media screen and (max-width: 389px) {
  #calendar .calendar-body .calendar-column {
    padding-right: 0;
  }
}

#calendar .calendar-body .calendar-column .date-cell {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-right: 9px;
}

#calendar .calendar-body .calendar-column .date-cell svg,
#calendar .calendar-body .calendar-column .date-cell img {
  margin-top: 5px;
  cursor: pointer;
}

@media screen and (min-width: 992px) {
  #calendar .calendar-body .calendar-column .date-cell svg,
  #calendar .calendar-body .calendar-column .date-cell img {
    display: none;
  }
}

.border-left-calendar {
  border-left: 1px solid #CAA8F5 !important;
}

@media screen and (max-width: 991px) {
  #calendar .calendar-body .booking-detail-popup {
    display: none;
    position: absolute;
    left: 48px;
    padding: 6px 8px;
    padding: 6px 8px;
    background-color: #F1F1F1;
    -webkit-filter: drop-shadow(4px 0px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px -4px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px 4px 7px rgba(0, 0, 0, 0.25));
    filter: drop-shadow(4px 0px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px -4px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px 4px 7px rgba(0, 0, 0, 0.25));
  }

  .multiple-booking {
    display: none;
  }
}

#calendar .calendar-body .booking-detail-popup .booking-detail-title,
#calendar .calendar-body .booking-detail-popup .booking-detail-location,
#calendar .calendar-body .booking-detail-popup .booking-detail-time {
  font-family: 'Montserrat', sans-serif;
  font-size: 9px;
  line-height: 10.97px;
}

#calendar .calendar-body .booking-detail-popup .booking-detail-title {
  color: #690FAD;
  font-weight: 500;
}

#calendar .calendar-body .booking-detail-popup .booking-detail-location,
#calendar .calendar-body .booking-detail-popup .booking-detail-time {
  color: #000000;
  font-weight: 400;
  margin-bottom: 6px;
}

#calendar {
  margin-top: 14px;
  margin-bottom: 69px;
}

.month-view {
  height: 500px
}

.week-view {
  height: 200px
}
</style>