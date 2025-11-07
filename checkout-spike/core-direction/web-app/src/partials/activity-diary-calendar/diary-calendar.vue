<template>
  <div>
    <SocialSharingOnSignup
      :show-modal="showSharingModal"
      :slug-url="eventDetail.slug_url"
      @setShowActivityModal="hideSocialSharingModal"
    />
    <activity-diary-detail
      v-if="isShownEventDetailModal"
      :event-detail="eventDetail"
      @inviteFriend="inviteFriendModalShow"
      @onCancelledActivityDiary="cancelModal"
      @onCloseActivityDiaryDetail="closeActivityDiaryModal"
      @refetchActivities="refetchActivitiesOnJoin"
      @socialSharing="showSharingModal = true"
    />
    <multiple-activity-diary
      :date-and-day="dateAndDay"
      :is-show-detail-modal-prop="isShowDetailModal"
      :multiple-events="eventLists"
      @hideDetailModal="hideDetailModal"
      @onShowActivityDetail="showActivityDetail"
    />
    <invite-friend-modal
      v-if="isShowInviteFriendModal"
      :activity-id="eventDetail.id"
      :is-calendar-modal="true"
      @goBackToActivityCalendar="goBackToActivityCalendar"
      @setShowActivityModal="goBackToActivityCalendar"
    />
    <calendar-modal
      :activity-detail="eventDetail"
      :is-modal-show-prop="isModalShow"
      @cancelBooking="cancelBooking"
      @onCancelBooking="$emit('onBookingCancelled')"
      @onHideCalendarModal="hideCalendarModal"
      @onShareActivityWithYourFriends="shareActivity"
    />
    <cancel-booking-modal
      v-if="isCancelModalShow"
      :activity-detail="eventDetail"
      @cancelledBooking="updateGetDiaryPayload"
      @close="closeCancelBookingModal"
    />
    <section id="calendar" :class="getCalendarView">
      <div class="custom-container">
        <div class="section-header flex items-center justify-between">
          <div class="title-box">
            <h4 class="section-title capitalize">
              Calendar
            </h4>
          </div>
        </div>
        <UserActivityCalendarHeader
          :calendarType="calendarType"
          :currentMonthName="currentMonthName"
          :currentYear="currentYear"
          @nextMonth="next"
          @prevMonth="prev"
          @switchCalendar="switchCalendar"
        />
        <UserActivityCalendarBody
          :currentDayEvents="getDayEvent()"
          :days="days"
          :pastMonthDays="getPastMonthDay()"
          @showActivityDetail="showActivityDetail"
          @showActivityModal="showActivityModal"
          @showEventDetailModal="showEventDetailModal"
        />
      </div>
    </section>
  </div>
</template>

<script>
import moment from "moment";
import {getActivityDairyDetailBySlug, getUserActivityDiary, getUserActivityDiaryDetail} from "@/apiManager/diary";
import MultipleActivityDiary from "@/partials/activity-diary-calendar/multiple-activity-diary";
import ActivityDiaryDetail from "@/partials/activity-diary-calendar/activity-diary-detail";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import Emitter from "tiny-emitter/instance";
import {getFormattedTime, showBodyScroll} from "@/utils";
import InviteFriendModal from "@/components/InviteFriendModal";
import {ACTIVITY_TYPES} from "@/common/constants/constants";
import {getCalendarActivityTypes} from "@/common/utilities/utility";
import generalMixin from "@/mixin/generalMixin";
import CalendarModal from "@/partials/calendar/calendar-modal.vue";
import CancelBookingModal from "@/partials/event-calendar/cancel-booking-modal.vue";
import {mapGetters} from "vuex";
import {UserActivityCalendarBody, UserActivityCalendarHeader} from "@/components/UserActivityCalendar";

export default {
  name: "DiaryCalendar",
  components: {
    CancelBookingModal,
    CalendarModal,
    InviteFriendModal,
    SocialSharingOnSignup,
    ActivityDiaryDetail,
    MultipleActivityDiary,
    UserActivityCalendarHeader,
    UserActivityCalendarBody
  },
  props: {
    username: {
      type: String,
      default: null,
    },
  },
  mixins: [generalMixin],
  data() {
    return {
      currentDate: new Date().getUTCDate(),
      currentMonth: new Date().getMonth(),
      currentYear: new Date().getFullYear(),
      days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      calendarType: "weekly",
      isModalShow: false,
      calendarTypes: {
        WEEKLY: "weekly",
        MONTHLY: "monthly",
      },
      isShowInviteFriendModal: false,
      events: [],
      eventLists: [],
      eventDetail: {},
      event: {},
      dateAndDay: {
        day: "",
        date: "",
      },
      dayStart: null,
      isShowDetailModal: false,
      isCancelModalShow: false,
      allEventLists: {},
      activityDiaryPayload: {
        dateFrom: "",
        dateTo: "",
        username: this.username,
        limit: 0,
        offset: 0,
      },
      isShownEventDetailModal: false,
      showSharingModal: false,
      ACTIVITY_TYPES,
    };
  },
  methods: {
    isActivityDiaryShared() {
      if (
        this.$route.name === "Community" &&
          this.$route.query.dairy_shared === "true" &&
          this.$route.query.slug !== undefined
      ) {
        return true;
      }
      return false;
    },
    refetchCalendarData() {
      if (this.isActivityDiaryShared()) {
        this.getActivityDairyDetail(this.$route.query.slug);
      }
      Emitter.on("refetch_activity_diary_listing", () => {
        this.updateGetDiaryPayload();
      });
    },

    goBackToActivityCalendar() {
      this.isShownEventDetailModal = true;
      this.isShowInviteFriendModal = false;
    },
    inviteFriendModalShow() {
      this.isShowInviteFriendModal = true;
      this.isShownEventDetailModal = false;
    },

    hideSocialSharingModal() {
      this.showSharingModal = false;
      showBodyScroll();
    },
    getTime(date) {
      return getFormattedTime(date);
    },
    cancelBooking() {
      this.isModalShow = false;
      this.isCancelModalShow = true;
    },
    hideCalendarModal() {
      this.isModalShow = false;
    },
    getUserActivityDiary(payload) {
      getUserActivityDiary(payload)
        .then((response) => {
          this.events = response.data;
        })
        .catch((error) => toastr.error(error));
    },
    showActivityModal(event, date) {
      if (event.length === 1) {
        this.showActivityDetail(event);
        return false;
      }
      this.showEventDetailModal(event, date);
      return false;
    },
    refetchActivitiesOnJoin() {
      this.closeActivityDiaryModal();
      this.updateGetDiaryPayload();
    },
    showActivityDetailModal(event) {
      this.event = event;
      this.isShowDetailModal = false;
      this.isModalShow = true;
    },
    hideDetailModal() {
      this.isShowDetailModal = false;
    },
    showEventDetailModal(events, date) {
      this.isShowDetailModal = true;
      this.eventLists = events;
      this.dateAndDay.day = this.getDayName(date);
      this.dateAndDay.date = date;
    },
    async showActivityDetail(event) {
      const {type, slug_url, id} = Array.isArray(event) ? event[0] : event;
      this.eventDetail = {};
      this.eventDetail.type = type;
      let activityDiaryDetail = this.events[type]
        .filter((event) =>
          type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY
            ? event.user_diary_activity.id === id
            : event.id === id
        )
        .map((event) => event);
      if (type === ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY) {
        const eventD = await getUserActivityDiaryDetail(slug_url);
        this.eventDetail.profile_picture =
            this.userProfile().picture;
        Object.assign(
          this.eventDetail,
          eventD.data
        );
        this.isShownEventDetailModal = true;
      } else {
        this.eventDetail = activityDiaryDetail[0];
        this.isModalShow = true;
      }
      const body = document.querySelector("body");
      body.classList.add("overflow-y-hidden");
    },

    closeActivityDiaryModal() {
      this.toggleBodyOverflow();
      this.isShownEventDetailModal = false;
    },
    cancelModal(detail) {
      this.toggleBodyOverflow();
      this.isShownEventDetailModal = false;
      this.eventDetail = detail;
      this.isCancelModalShow = true;
    },
    toggleBodyOverflow() {
      const body = document.querySelector("body");
      body.classList.remove("overflow-y-hidden");
    },
    daysInMonth() {
      return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
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
      this.getPastMonthDay();
      this.updateGetDiaryPayload();
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
    getPastMonthDay() {
      if (this.calendarType === "monthly" || this.getWeekNumber() === 1) {
        return new Date(this.currentYear, this.currentMonth, 1).getDay();
      }
      return 0;
    },

    prev() {
      if (this.currentMonth === 0) {
        this.currentMonth = 11;
        this.currentYear--;
      } else {
        this.currentMonth--;
      }
      this.getPastMonthDay();
      this.updateGetDiaryPayload();
    },

    appendMonthAndYear(day) {
      return `${this.currentDateDoubleDigit(
        day
      )}-${this.currentMonthDoubleDigit()}-${this.currentYear}`;
    },
    getDayEvent() {
      const {startDay, endDay} = this.firstWeekRemainingDays();
      let eventDateObject = {};
      for (let day = startDay; day <= endDay; day++) {
        eventDateObject[this.appendMonthAndYear(day)] = [];
      }
      if (
        this.events[ACTIVITY_TYPES.ACTIVITY_TYPE_BOOKING] &&
          this.events[ACTIVITY_TYPES.ACTIVITY_TYPE_BOOKING].length
      ) {
        this.events[ACTIVITY_TYPES.ACTIVITY_TYPE_BOOKING].forEach((event) => {
          if (eventDateObject[event.class_date]) {
            eventDateObject[event.class_date].push({
              ...getCalendarActivityTypes("ACTIVITY_TYPE_BOOKING"),
              ...event,
            });
          }
        });
      }
      if (
        this.events[ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY] &&
          this.events[ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY].length
      ) {
        this.events[ACTIVITY_TYPES.ACTIVITY_TYPE_DIARY].forEach((event) => {
          if (
            eventDateObject[
              moment(event.user_diary_activity.date).utc().format("DD-MM-YYYY")
            ]
          ) {
            eventDateObject[
              moment(event.user_diary_activity.date).utc().format("DD-MM-YYYY")
            ].push({
              ...getCalendarActivityTypes("ACTIVITY_TYPE_DIARY"),
              ...event.user_diary_activity,
            });
          }
        });
      }
      return eventDateObject;
    },
    updateGetDiaryPayload() {
      this.activityDiaryPayload.dateFrom = this.dateFrom;
      this.activityDiaryPayload.dateTo = this.dateTo;
      this.getUserActivityDiary(this.activityDiaryPayload);
      this.$emit("onRefetchAllActivities");
    },
    firstWeekRemainingDays() {
      if (this.calendarType === this.calendarTypes.WEEKLY) {
        const today = new Date();
        const cToday =
            today.getMonth() === this.currentMonth
              ? today
              : new Date(this.currentYear, this.currentMonth, 1);
        const dayOfWeek = cToday.getDay();
        const weekDays = [];
        const weekStart = new Date(cToday);
        weekStart.setDate(weekStart.getDate() - dayOfWeek);
        for (let i = 0; i < 7; i++) {
          const day = new Date(weekStart);
          day.setDate(day.getDate() + i);
          if (day.getMonth() === cToday.getMonth()) {
            weekDays.push(day.getDate());
          }
        }
        return {startDay: weekDays[0], endDay: weekDays[weekDays.length - 1]};
      }
      return {startDay: 1, endDay: this.daysInMonth()};
    },
    getWeekNumber() {
      const today = new Date();
      const cToday =
          today.getMonth() === this.currentMonth
            ? today
            : new Date(this.currentYear, this.currentMonth, 1);
      const firstDayOfCurrentYear = new Date(
        cToday.getFullYear(),
        cToday.getMonth(),
        1
      );
      const pastDaysOfCurrentYear = (cToday - firstDayOfCurrentYear) / 86400000;
      return Math.ceil(
        (pastDaysOfCurrentYear + firstDayOfCurrentYear.getDay() + 1) / 7
      );
    },
    getActivityDairyDetail(slug) {
      let payload = {
        slugUrl: slug,
      };
      getActivityDairyDetailBySlug(payload)
        .then((response) => {
          this.eventDetail = response.data;
          this.isShownEventDetailModal = true;
        })
        .catch((error) => toastr.error(error));
    },
    shareActivity() {
      this.showSharingModal = true;
      this.isModalShow = false;
      if (this.eventDetail.hasOwnProperty("type") && this.eventDetail.type === "userActivityDairy") {
        Emitter.emit(
          "social_sharing_modal",
          "shareActivity",
          this.eventDetail.slug_url
        );
        return;
      } else {
        Emitter.emit(
          "social_sharing_modal",
          "booking",
          this.eventDetail.slug_url
        );
        return;
      }


    },
    closeCancelBookingModal() {
      this.isCancelModalShow = false;
    },
  },

  computed: {
    ...mapGetters({
      userProfile: "getStoreUserProfileGetters"
    }),
    currentMonthName() {
      return new Date(this.currentYear, this.currentMonth).toLocaleString(
        "default",
        {month: "long"}
      );
    },
    getCalendarView() {
      return this.calendarType === this.calendarTypes.MONTHLY
        ? "month-view"
        : "week-view";
    },

    getStartAndEndDayOfWeek() {
      const today = moment();
      const from_date = today.startOf("week").format("DD");
      const to_date = today.endOf("week").format("DD");
      return {startDay: from_date, endDay: to_date};
    },
    getCurrentMonth() {
      return new Date().getMonth();
    },
    dateFrom() {
      return `${this.currentYear}-${(this.currentMonth + 1).toString().padStart(2, "0")}-01`;
    },
    dateTo() {
      return `${this.currentYear}-${(this.currentMonth + 1).toString().padStart(2, "0")}-${this.daysInMonth()}`;
    },
  },
  mounted() {
    this.updateGetDiaryPayload();
  },
  watch: {
    "$store.state.isActivitySharePopupVisible": function (newVal) {
      if (newVal) {
        this.refetchCalendarData();
      }
    },
  },

  created() {
    this.refetchCalendarData();
  },
};
</script>

<style scoped>
::v-deep #calendar {
  margin-top: 80px;
  margin-bottom: 69px;
}

::v-deep #calendar .calendar-header {
  margin-top: 22px;
  margin-bottom: 22px;
}

::v-deep #calendar .calendar-info-box .selected-date {
  font-family: "Montserrat", sans-serif;
  font-size: 20px;
  line-height: 24.38px;
  font-weight: 400;
  margin-left: 16px;
  margin-right: 16px;
  color: #690fad;
}

@media screen and (max-width: 991px) {
  ::v-deep #calendar .calendar-info-box .selected-date {
    font-size: 14px;
    line-height: 21.94px;
  }
}

@media screen and (max-width: 413px) {
  ::v-deep #calendar .calendar-info-box .selected-date {
    font-size: 12px;
    margin-left: 5px;
    margin-right: 5px;
  }

  ::v-deep .calendar-info-box .prev-month svg,
  ::v-deep .calendar-info-box .next-month svg {
    max-height: 16px;
  }
}

::v-deep #calendar .calendar-info-box .selected-date .selected-month {
  margin-right: 12px;
}

@media screen and (max-width: 374px) {
  ::v-deep #calendar .calendar-info-box .selected-date .selected-month {
    margin-right: 4px;
  }

  ::v-deep #calendar .calendar-info-box .selected-date {
    margin-left: 3px;
    margin-right: 3px;
  }
}

::v-deep #calendar .calendar-info-box svg,
::v-deep #calendar .calendar-info-box path {
  fill: #690fad;
}

::v-deep #calendar .calendar-type-box {
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.1);
}

::v-deep #calendar .calendar-type-box .calendar-type-weekly,
::v-deep #calendar .calendar-type-box .calendar-type-monthly {
  color: #000000;
  background-color: #ffffff;
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  line-height: 12.63px;
  font-weight: 700;
  padding: 10px 25px;
}

@media screen and (max-width: 767px) {
  ::v-deep #calendar .calendar-type-box .calendar-type-weekly,
  ::v-deep #calendar .calendar-type-box .calendar-type-monthly {
    padding: 10px 21px;
  }
}

@media screen and (max-width: 374px) {
  ::v-deep #calendar .calendar-type-box .calendar-type-weekly,
  ::v-deep #calendar .calendar-type-box .calendar-type-monthly {
    padding: 8px 10px;
  }
}

::v-deep #calendar .calendar-type-box .calendar-type-weekly:hover,
::v-deep #calendar .calendar-type-box .calendar-type-monthly:hover {
  color: #ffffff;
  background-color: #690fad;
}

::v-deep #calendar .calendar-type-box .calendar-type-weekly {
  border-radius: 30px 0 0 30px;
}

::v-deep #calendar .calendar-type-box .calendar-type-monthly {
  border-radius: 0 30px 30px 0;
}

::v-deep .calendar-column:nth-child(7n-6) {
  border-left: 1px solid #caa8f5 !important;
}

::v-deep #calendar .calendar-type-box .active {
  color: #ffffff;
  background-color: #690fad;
}

::v-deep #calendar .calendar-body .calendar-row {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
}

::v-deep #calendar .calendar-body .calendar-row:first-child {
  min-height: 46px;
  max-height: 46px;
}

::v-deep #calendar .calendar-body .calendar-row:first-child .calendar-column {
  padding: 8px 15px;
  min-height: 46px;
  max-height: 46px;
  font-weight: 600;
  color: #690fad;
}

@media screen and (max-width: 767px) {
  ::v-deep #calendar .calendar-body .calendar-row:first-child .calendar-column {
    padding: 6px;
  }
}

::v-deep #calendar .calendar-body .calendar-column {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  font-family: "Montserrat", sans-serif;
  font-size: 11px;
  font-weight: 400;
  line-height: 13.41px;
  border-right: 1px solid #caa8f5;
  padding: 17px 5px 5px 21px;
  min-height: 69px;
  max-height: 69px;
  border-bottom: 1px solid #36363626 !important;
}

@media screen and (max-width: 991px) {
  ::v-deep #calendar .calendar-body .calendar-column {
    position: relative;
  }
}

@media screen and (max-width: 767px) {
  ::v-deep #calendar .calendar-body .calendar-column {
    padding-top: 14px;
    padding-left: 6px;
  }
}

@media screen and (max-width: 389px) {
  ::v-deep #calendar .calendar-body .calendar-column {
    padding-right: 0;
  }
}

::v-deep #calendar .calendar-body .calendar-column .date-cell {
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-right: 9px;
}

::v-deep #calendar .calendar-body .calendar-column .date-cell svg,
::v-deep #calendar .calendar-body .calendar-column .date-cell img {
  margin-top: 5px;
  cursor: pointer;
}

@media screen and (min-width: 992px) {
  ::v-deep #calendar .calendar-body .calendar-column .date-cell svg,
  ::v-deep #calendar .calendar-body .calendar-column .date-cell img {
    display: none;
  }
}

::v-deep .border-left-calendar {
  border-left: 1px solid #caa8f5 !important;
}

@media screen and (max-width: 991px) {
  ::v-deep #calendar .calendar-body .booking-detail-popup {
    display: none;
    position: absolute;
    left: 48px;
    padding: 6px 8px;
    padding: 6px 8px;
    background-color: #f1f1f1;
    -webkit-filter: drop-shadow(4px 0px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px -4px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px 4px 7px rgba(0, 0, 0, 0.25));
    filter: drop-shadow(4px 0px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px -4px 7px rgba(0, 0, 0, 0.25)) drop-shadow(0px 4px 7px rgba(0, 0, 0, 0.25));
  }

  ::v-deep .multiple-booking {
    display: none;
  }
}

::v-deep #calendar .calendar-body .booking-detail-popup .booking-detail-title,
::v-deep #calendar .calendar-body .booking-detail-popup .booking-detail-location,
::v-deep #calendar .calendar-body .booking-detail-popup .booking-detail-time {
  font-family: "Montserrat", sans-serif;
  font-size: 9px;
  line-height: 10.97px;
}

::v-deep #calendar .calendar-body .booking-detail-popup .booking-detail-title {
  color: #690fad;
  font-weight: 500;
}

::v-deep #calendar .calendar-body .booking-detail-popup .booking-detail-location,
::v-deep #calendar .calendar-body .booking-detail-popup .booking-detail-time {
  color: #000000;
  font-weight: 400;
  margin-bottom: 6px;
}

::v-deep #calendar {
  margin-top: 14px;
  margin-bottom: 69px;
}

::v-deep .month-view {
  min-height: 500px;
}

::v-deep .week-view {
  height: 200px;
}
</style>