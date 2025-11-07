<template>
  <div>
    <activity-diary-detail v-if="isEventDetailModalVisible"
                           :event-detail-data="eventDetail"
                           @inviteFriendEvent="inviteFriendModalShow"
                           @onCloseEventDetailModal="closeActivityDiaryDetail"
                           @openSocialSharingModal="showSocialSharingModal"
    />
    <section id="calendar" :class="`${isCurrentWeek ? 'week-view' : 'month-view' }`">
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
              <activity-diary-previous />
            </button>
            <p class="selected-date">
              <span class="selected-month">{{ currentMonthName }}</span>
              <span class="selected-year">{{ currentYear }}</span>
            </p>
            <button class="next-month" @click="next">
              <activity-diary-next />
            </button>
          </div>
          <div class="calendar-type-box flex items-center rounded-full">
            <button :class="['calendar-type-weekly', isCurrentWeek && 'active']"
                    @click="switchCalendar('weekly')"
            >
              Weekly
            </button>
            <button :class="['calendar-type-monthly', !isCurrentWeek && 'active']"
                    @click="switchCalendar('monthly')"
            >
              Monthly
            </button>
          </div>
        </div>
        <div class="calendar-body">
          <div class="calendar-row">
            <div v-for="(day,index) in days" :key="index" :class="{'border-left-calendar': index === 0}"
                 class="calendar-column"
            >
              {{ day }}
            </div>
          </div>
          <div :class="`calendar-row ${isCurrentWeek ? 'full-month-calendar':''}`">
            <div v-for="sDay in startDay()" :key="`${currentMonth}-${currentYear}-previous-${sDay}`"
                 class="calendar-column"
            >
              &nbsp;
            </div>
            <div v-for="(activities,cDay) in events" :key="`${currentMonth}-${currentYear}-current-${cDay}`"
                 class="calendar-column"
            >
              <div class="date-cell flex">
                {{ cDay }} <span class="md:hidden d-block" @click="openEventDetailModal(activities[0])">
                  <activity-diary-detail-icon />
                </span>
              </div>
              <div v-if="activities.length === 1" class="booking-detail-popup cursor-pointer"
                   @click="openEventDetailModal(activities[0])"
              >
                <p class="booking-detail-title uppercase">
                  {{ getActivityName(activities[0]) }}
                </p>
                <p class="booking-detail-location">
                  {{ getActivityTypeName(activities[0]) }}
                </p>
                <p class="booking-detail-time">
                  {{ getActivityStartTime(activities[0]) }} - {{ getActivityEndTime(activities[0]) }}
                </p>
              </div>
              <div v-if="activities.length > 1"
                   class="booking-detail-popup multiple-booking multiple-detail-booking-modal cursor-pointer"
              >
                <p class="booking-detail-title uppercase multiple-detail-booking-modal">
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
      </div>
    </section>
  </div>
</template>

<script>
import ActivityDiaryPrevious from "@/svgs/activity-diary/activity-diary-previous";
import ActivityDiaryNext from "@/svgs/activity-diary/activity-diary-next";
import {getUserActivityDiary} from "@/apiManager/diary";
import ActivityDiaryDetailIcon from "@/svgs/activity-diary/activity-diary-detail-icon";
import ActivityDiaryDetail from "@/partials/activity-diary-calendar/activity-diary-detail";

export default {
    name: "EventCalendar",
    components: {
        ActivityDiaryDetail,
        ActivityDiaryDetailIcon,
        ActivityDiaryNext,
        ActivityDiaryPrevious
    },
    props: {
        username: {
            type: String,
            default: "",
        }
    },
    filters: {
        convertToTimeUTC(date) {
            return new Date(date).toLocaleTimeString("en", {
                timeStyle: "short",
                hour12: false,
                timeZone: "UTC"
            });
        }
    },
    data() {
        return {
            currentDate: new Date().getUTCDate(),
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            isCurrentWeek: false,
            events: [],
            isEventDetailModalVisible: false,
            eventDetail: {}
        };
    },
    methods: {
        getActivityName(activity) {
            return activity.type === "diary"
                ? activity.user_diary_activity.activity_name
                : activity.activityName;
        },
        getActivityTypeName(activity) {
            return activity.type === "diary"
                ? activity.user_diary_activity.activity_type.name
                : activity.actt_name;
        },
        getActivityStartTime(activity) {
            return activity.type === "diary"
                ? this.$options.filters.convertToTimeUTC(activity.user_diary_activity.start_time)
                : activity.startTime;
        },
        getActivityEndTime(activity) {
            return activity.type === "diary"
                ? this.$options.filters.convertToTimeUTC(activity.user_diary_activity.end_time)
                : activity.endTime;
        },
        daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },
        startDay() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },
        next() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },
        prev() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },
        switchCalendar(type) {
            this.isCurrentWeek = type === "weekly" ? true : false;
        },
        async getUserEvents(payload) {
            try {
                const {data} = await getUserActivityDiary(payload);
                const dates = {};
                for (let day = 1; day <= this.daysInMonth(); day++) {
                    let dayActivities = [];
                    data.userActivityDairy.forEach((activity) => {
                        const extractedDate = this.extractDate(activity.user_diary_activity.date);
                        if (extractedDate === day) {
                            dayActivities.push({...activity, type: "diary"});
                        }
                    });
                    data.userBookings.forEach((activity) => {
                        const extractedDate = this.extractStringDate(activity.class_date);
                        if (extractedDate === day) {
                            dayActivities.push({...activity, type: "booking"});
                        }
                    });
                    dates[day] = dayActivities;
                }
                this.events = dates;
            } catch (error) {
                toastr.error(error);
            }
        },
        extractDate(date) {
            return Number(date.substring(8, 10));
        },
        extractStringDate(date) {
            return Number(date.split("-")[0]);
        },
        getCalendarPayload() {
            return {
                "dateFrom": `${this.currentYear}-${this.currentMonth + 1}-01`,
                "dateTo": `${this.currentYear}-${this.currentMonth + 1}-${this.daysInMonth()}`,
                "username": this.username,
                "limit": 0,
                "offset": 0
            };
        }

    },
    async created() {
        await this.getUserEvents(this.getCalendarPayload());
    },

    computed: {
        currentMonthName() {
            return new Date(
                this.currentYear,
                this.currentMonth
            ).toLocaleString("default", {month: "long"});
        },
    },

};
</script>

<style scoped>
.full-month-calendar div {
  display: none !important;
}

.full-month-calendar div:nth-child(-n+7) {
  display: block !important;
}

#calendar {
  margin-top: 80px;
  margin-bottom: 69px;
}

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
  min-height: 500px
}

.week-view {
  height: 200px
}

</style>