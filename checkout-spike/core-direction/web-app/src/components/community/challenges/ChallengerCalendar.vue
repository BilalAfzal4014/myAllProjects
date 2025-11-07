<template>
  <div id="challenger-calendar-modal" class="custom-modal m-auto">
    <FriendModal v-if="isShowFriendModal" :challenge-detail="true" :user="selectedUserData"
                 @closeFriendModal="hideFriendModal"
                 @updateUserObject="updateUserObject"
    />
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="$parent.showCalendar = false">
              <close-blue-icon />
            </div>
          </div>
          <div class="modal-body px-5">
            <div class="form-container mx-auto">
              <p class="challengers-modal-title">
                {{ challengeDetail.title }}
              </p>
              <p class="challengers-modal-desc">
                <span class="start-date">Start Date on  {{ setDateFormat(challengeDetail.start_date) }} </span>
                <span class="start-date">-</span>
                <span class="end-date">End Date on {{ setDateFormat(challengeDetail.end_date) }}</span>
              </p>
            </div>
            <div class="challenger-calendar-box flex items-end justify-between">
              <div class="activity-score-box">
                <ul class="score-point-list flex items-center justify-center flex-col">
                  <li v-for="n in 10" :key="n" :class="(11 - n) <= todayScore ? 'active' : ''" class="score-point-item">
                    <core-points-icon />
                  </li>
                </ul>
                <p class="score-title">
                  Today’s Score: {{ todayScore }}
                </p>
                <p class="score-desc">
                  Total Core Points: {{ totalCorePoints }}
                </p>
              </div>
              <div class="calendar-outer-box">
                <div class="calendar-header flex items-center justify-between">
                  <button class="btn-prev-month flex items-center active" @click="previousMonth">
                    <back-arrow-blue-icon />
                    Previous
                  </button>
                  <p class="start-date">
                    {{ this.selectedMonth | monthName }}
                  </p>
                  <button class="btn-next-month flex items-center active" @click="nextMonth">
                    Next
                    <forward-arrow-blue-icon />
                  </button>
                </div>
                <div class="calendar-body">
                  <div class="calendar-thead">
                    <div class="calendar-tr">
                      <div v-for="(day,index) in days" :key="index" class="calendar-th">
                        {{ day }}
                      </div>
                    </div>
                  </div>
                  <div class="calendar-tbody">
                    <div v-for="(item, index) in gridArray" :key="index" class="calendar-tr">
                      <div v-for="(data, i) in item" :key="i" :class="monthCheck(data)"
                           class="calendar-td flex flex-col" @click="getDateCorePoints(data,'full');setTodayDate(data)"
                      >
                        <p class="date">
                          {{ data.getDate() }}
                        </p>
                        <p class="point-scored">
                          {{ getDateCorePoints(data, "core") }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <ul class="activity-stats-list flex items-center flex-wrap">
              <li class="activity-stats-item flex items-center">
                <div class="activity-icon-box">
                  <activity-log-icon :height="25" :width="24" />
                </div>
                <div class="activity-content-box">
                  <p class="activity-label">
                    Activity Log
                  </p>
                  <p class="activity-points">
                    {{ logActivityCount }}
                  </p>
                  <p class="activity-label">
                    Today
                  </p>
                </div>
              </li>
              <li class="activity-stats-item flex items-center">
                <div class="activity-icon-box">
                  <booking-acitiviy-icon :height="25" />
                </div>
                <div class="activity-content-box">
                  <p class="activity-label">
                    Bookings
                  </p>
                  <p class="activity-points">
                    {{ checkinCount }}
                  </p>
                  <p class="activity-label">
                    Today
                  </p>
                </div>
              </li>
              <li class="activity-stats-item flex items-center">
                <div class="activity-icon-box">
                  <activity-steps-icon :height="25" />
                </div>
                <div class="activity-content-box">
                  <p class="activity-label">
                    Steps
                  </p>
                  <p class="activity-points">
                    {{ stepsCount }}
                  </p>
                  <p class="activity-label">
                    Today
                  </p>
                </div>
              </li>
              <li class="activity-stats-item flex items-center">
                <div class="activity-icon-box">
                  <heart-rate-icon :height="24" :width="24" />
                </div>
                <div class="activity-content-box">
                  <p class="activity-label">
                    Heart Rate
                  </p>
                  <p class="activity-points">
                    {{ heartRateCount }}
                  </p>
                  <p class="activity-label">
                    Active Min
                  </p>
                </div>
              </li>
              <li class="activity-stats-item flex items-center">
                <div class="activity-icon-box">
                  <on-demand-video-icon :height="33" :width="24" />
                </div>
                <div class="activity-content-box">
                  <p class="activity-label">
                    On Demand
                  </p>
                  <p class="activity-points">
                    {{ onDemandCount }}
                  </p>
                  <p class="activity-label">
                    Today
                  </p>
                </div>
              </li>
            </ul>
            <ul class="tbody challenger-list-footer" @click="navigateToUserProfile">
              <li class="tr challenger-item">
                <div class="td flex items-center">
                  <div class="ranker-img-box">
                    <img :src="getImageUrl(selectedUserData.profile_picture)" alt=""
                         class="rounded-full"
                    >
                  </div>
                  <div class="ranker-info-box">
                    <p class="ranker-name">
                      {{ selectedUserData.firstname }} {{ selectedUserData.lastname }}
                    </p>
                    <p class="ranker-core-points">
                      {{ selectedUserData.totalCorePoints }} Points
                    </p>
                  </div>
                </div>
                <div class="td flex items-center justify-end">
                  <div class="ranking-img-box rounded-full">
                    <rank-icon />
                  </div>
                  <div class="ranking-info-box">
                    <p class="ranking-title">
                      Rank
                    </p>
                    <p class="ranking-number">
                      {{ selectedUserData.rank }}
                    </p>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import moment from "moment";
import DefaultImage from "@/assets/images/default_profile_img.png";
import RankIcon from "@/svgs/rank-icon";
import CloseBlueIcon from "@/svgs/close-blue-icon";
import CorePointsIcon from "@/svgs/community/core-points-icon";
import BackArrowBlueIcon from "@/svgs/arrows/back-arrow-blue-icon";
import ActivityLogIcon from "@/svgs/community/activity-log-icon";
import BookingAcitiviyIcon from "@/svgs/community/booking-acitiviy-icon";
import ActivityStepsIcon from "@/svgs/community/activity-steps-icon";
import HeartRateIcon from "@/svgs/community/heart-rate-icon";
import OnDemandVideoIcon from "@/svgs/wearable-modal-icons/on-demand-video-icon";
import FriendModal from "@/partials/group/FriendModal";
import {mapGetters} from "vuex";
import {PRIVACY_TYPE} from "@/common/constants/constants";
import ForwardArrowBlueIcon from "@/svgs/arrows/forward-arrow-blue-icon";

export default {
  name: "ChallengerCalendar",
  components: {
    ForwardArrowBlueIcon,
    FriendModal,
    OnDemandVideoIcon,
    HeartRateIcon,
    ActivityStepsIcon,
    BookingAcitiviyIcon,
    ActivityLogIcon,
    BackArrowBlueIcon, CorePointsIcon, CloseBlueIcon, RankIcon
  },
  data() {
    return {
      days: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      today: new Date(),
      currentMonth: new Date().getMonth(),
      currentYear: new Date().getFullYear(),
      filterDate: undefined,
      selectedMonth: new Date(),
      dateData: {},
      currentDateObj: new Date(),
      isShowFriendModal: false,

    };
  },
  props: {
    gamificationData: {
      type: Array,
      default: null
    },
    challengeDetail: {
      type: Object,
      default: null
    },
    selectedUserData: {
      type: Object,
      default: null
    }
  },
  mounted() {
    this.getDateCorePoints(new Date(), "full");
  },
  filters: {
    monthName(dateString) {
      const MONTH_NAMES = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      const date = new Date(dateString);
      return MONTH_NAMES[date.getMonth()];
    }
  },
  methods: {
    navigateToUserProfile() {
      if (!this.selectedUserData) return;

      const {id, privacy, username} = this.selectedUserData;
      let routePath = "";

      if (id === this.userProfile().id) {
        routePath = "/community";
      } else if (privacy === PRIVACY_TYPE.PUBLIC) {
        routePath = `/profile/${username}`;
      } else if (privacy === PRIVACY_TYPE.PRIVATE) {
        routePath = `/private/${username}`;
      }

      if (routePath) {
        this.$router.push(routePath);
      }
    },
    showFriendModal() {
      this.isShowFriendModal = true;
    },
    hideFriendModal() {
      this.isShowFriendModal = false;
    },

    setTodayDate(date) {
      this.currentDateObj = date;
    },
    updateUserObject(event) {
      this.$emit("updateUserObject", event);
    },
    getPointClass(n) {
      const isActive = n > (10 - (typeof this.dateData !== "undefined" ? this.dateData.todayCorePoints : -1));
      return {active: isActive};
    },
    previousMonth: function () {
      let tmpDate = this.selectedMonth;
      let tmpMonth = tmpDate.getMonth() - 1;
      this.selectedMonth = new Date(tmpDate.setMonth(tmpMonth));
    },
    nextMonth: function () {
      let tmpDate = this.selectedMonth;
      let tmpMonth = tmpDate.getMonth() + 1;
      this.selectedMonth = new Date(tmpDate.setMonth(tmpMonth));
    },
    getCalendarMatrix: function (date) {
      let calendarMatrix = [];

      let startDay = new Date(date.getFullYear(), date.getMonth(), 1);
      let lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

      // Modify the result of getDay so that we treat Monday = 0 instead of Sunday = 0
      let startDow = (startDay.getDay() + 6) % 7;
      let endDow = (lastDay.getDay() + 6) % 7;

      // If the month didn't start on a Monday, start from the last Monday of the previous month
      startDay.setDate(startDay.getDate() - startDow);

      // If the month didn't end on a Sunday, end on the following Sunday in the next month
      lastDay.setDate(lastDay.getDate() + (6 - endDow));

      let week = [];
      while (startDay <= lastDay) {
        week.push(new Date(startDay));
        if (week.length === 7) {
          calendarMatrix.push(week);
          week = [];
        }
        startDay.setDate(startDay.getDate() + 1);
      }

      return calendarMatrix;
    },
    monthCheck(data) {
      if (data.getMonth() === this.currentDateObj.getMonth()) {
        if (data.getDate() === this.currentDateObj.getDate()) {
          return "today";
        }
        return "";
      }
      return "not-selected-month";
    },
    getDateCorePoints: function (date, key) {
      const calendarDate = moment.parseZone(date).format("YYYY-MM-DD");
      const result = this.gamificationData.find(data => moment.parseZone(data.date).format("YYYY-MM-DD") === calendarDate);
      if (!result) {
        return;
      }

      if (key === "core") {
        return result.coreData.todayCorePoints;
      }

      if (key === "full") {
        this.dateData = result;
      }
    },

    setDateFormat(date) {
      return moment(date).format("MMM DD YYYY");
    },
    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl(`${imagePath}?optimizer=image&format=webp&width=88&aspect_ratio=1:1&sharpen=true`) : DefaultImage;
    },
  },
  computed: {
    ...mapGetters({
      userProfile: "getStoreUserProfileGetters",
    }),
    gridArray: function () {
      return this.getCalendarMatrix(this.selectedMonth);
    },
    logActivityCount() {
      return this.dateData?.coreData?.logActivityCount ?? "00";
    },
    checkinCount() {
      return this.dateData?.coreData?.checkinCount ?? "00";
    },
    stepsCount() {
      return this.dateData?.coreData?.stepsCount ?? "00";
    },
    onDemandCount() {
      return this.dateData?.coreData?.onDemandCount ?? "00";
    },
    heartRateCount() {
      return this.dateData?.coreData?.heartRateCount ?? "00";
    },
    todayScore() {
      return this.dateData?.coreData?.todayCorePoints ?? "0";
    },
    totalCorePoints() {
      return this.dateData?.coreData?.totalCorePoints ?? "0";
    }


  }
};
</script>

<style scoped>
#challenger-calendar-modal .modal-header {
  padding-top: 13px;
  padding-right: 20px;
  padding-bottom: 0;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .modal-header {
    padding-top: 17px;
    padding-right: 18px;
    position: absolute;
    right: 0;
  }

  #challenger-calendar-modal .modal-header svg {
    max-width: 28px;
  }
}

#challenger-calendar-modal .modal-header svg,
#challenger-calendar-modal .modal-header path {
  fill: #690FAD;
}

#challenger-calendar-modal .modal-outer-box {
  position: relative;
  max-width: 550px;
  background: #FFFFFF;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 21px;
}

#challenger-calendar-modal .form-container {
  width: 100%;
  max-width: 500px;
}

#challenger-calendar-modal .challengers-modal-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 5px;
  color: #690FAD;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .challengers-modal-title {
    padding-top: 30px;
    font-size: 18px;
    line-height: 22px;
  }
}

#challenger-calendar-modal .challengers-modal-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 700;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .challengers-modal-desc {
    font-size: 10px;
    line-height: 12px;
  }
}

#challenger-calendar-modal .challengers-modal-desc .start-date {
  margin-right: 10px;
}

#challenger-calendar-modal .tr {
  width: 100%;
  display: grid;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  grid-template-columns: 2fr 1fr;
  margin-bottom: 10px;
}

@media screen and (max-width: 767px) {
  #challenger-calendar-modal .tr {
    grid-template-columns: 1fr 1fr;
  }
}

@media screen and (max-width: 389px) {
  #challenger-calendar-modal .tr {
    grid-template-columns: 2fr 1fr;
  }
}

#challenger-calendar-modal .tbody {
  margin-top: 30px;
  margin-bottom: 17px;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .tbody {
    margin-top: 15px;
    margin-bottom: 6px;
  }
}

#challenger-calendar-modal .tbody .tr {
  color: #000000;
  background-color: #FFFFFF;
  -webkit-box-shadow: 1px 3px 7px rgba(0, 0, 0, 0.15);
  box-shadow: 1px 3px 7px rgba(0, 0, 0, 0.15);
  border-radius: 11px;
}

@media screen and (min-width: 992px) {
  #challenger-calendar-modal .tbody .tr {
    padding: 15px 21px;
    height: 74px;
  }
}

@media screen and (max-width: 991px) {
  #challenger-calendar-modal .tbody .tr {
    padding: 13px 9px;
    height: 60px;
  }
}

#challenger-calendar-modal .ranking-info-box {
  width: 100%;
  max-width: 70px;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .ranking-info-box {
    max-width: 44px;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .ranking-info-box {
    max-width: -webkit-fit-content;
    max-width: -moz-fit-content;
    max-width: fit-content;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .td.justify-end {
    -webkit-column-gap: 9px;
    column-gap: 9px;
  }

  #challenger-calendar-modal .btn-modal-close {
    width: 28px;
  }
}

#challenger-calendar-modal .ranking-img-box {
  margin-right: 15px;
  width: 100%;
  max-width: 40px;
}

#challenger-calendar-modal .ranking-img-box img,
#challenger-calendar-modal .ranking-img-box svg {
  width: 100%;
  max-width: 40px;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .ranking-img-box {
    margin-right: 5px;
    max-width: 28px;
  }

  #challenger-calendar-modal .ranking-img-box img,
  #challenger-calendar-modal .ranking-img-box svg {
    max-width: 28px;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .ranking-img-box {
    margin-right: 0;
  }
}

#challenger-calendar-modal .ranking-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: left;
}

#challenger-calendar-modal .ranking-number {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 700;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .ranking-number {
    font-size: 14px;
    line-height: 17px;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .ranking-number {
    font-size: 10px;
    line-height: 12px;
  }
}

#challenger-calendar-modal .ranker-img-box {
  margin-left: 30px;
  margin-right: 20px;
  width: 100%;
  max-width: 44px;
}

#challenger-calendar-modal .ranker-img-box img,
#challenger-calendar-modal .ranker-img-box svg {
  width: 100%;
  max-width: 44px;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .ranker-img-box {
    margin-left: 10px;
    margin-right: 9px;
    max-width: 34px;
  }

  #challenger-calendar-modal .ranker-img-box img,
  #challenger-calendar-modal .ranker-img-box svg {
    max-width: 34px;
  }
}

#challenger-calendar-modal .ranker-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .ranker-name {
    font-size: 12px;
    line-height: 15px;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .ranker-name {
    font-size: 10px;
  }
}

#challenger-calendar-modal .ranker-core-points {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .ranker-core-points {
    font-size: 12px;
    line-height: 15px;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .ranker-core-points {
    font-size: 10px;
  }
}

#challenger-calendar-modal .challenger-calendar-box {
  margin-top: 30px;
  margin-bottom: 30px;
}

#challenger-calendar-modal .challenger-list-footer {
  margin-top: 0;
  margin-bottom: 0;
  margin-left: -1.25rem;
  margin-right: -1.25rem;
}

#challenger-calendar-modal .challenger-list-footer .tr {
  color: #FFFFFA;
  background: #690FAD;
  margin-bottom: 0;
  border-radius: 0px 0px 21px 21px;
  padding-left: 50px;
  padding-right: 50px;
}

@media (max-width: 389px) {
  #challenger-calendar-modal .challenger-list-footer .tr {
    padding-left: 20px;
    padding-right: 20px;
  }
}

#challenger-calendar-modal .challenger-list-footer p {
  color: #FFFFFA;
}

#challenger-calendar-modal .challenger-list-footer .btn-rank svg,
#challenger-calendar-modal .challenger-list-footer .btn-rank path {
  fill: #F2F5EA;
}

#challenger-calendar-modal .other-challenger.challenger-list-footer {
  margin-top: 0;
  margin-bottom: 0;
  margin-left: -1.25rem;
  margin-right: -1.25rem;
}

#challenger-calendar-modal .other-challenger.challenger-list-footer .tr {
  color: #06070E;
  background: #F2F5EA;
  margin-bottom: 0;
  border-radius: 0px 0px 21px 21px;
  padding-left: 50px;
  padding-right: 50px;
}

@media (max-width: 389px) {
  #challenger-calendar-modal .other-challenger.challenger-list-footer .tr {
    padding-left: 20px;
    padding-right: 20px;
  }
}

#challenger-calendar-modal .other-challenger.challenger-list-footer p {
  color: #06070E;
}

#challenger-calendar-modal .other-challenger.challenger-list-footer .btn-rank svg,
#challenger-calendar-modal .other-challenger.challenger-list-footer .btn-rank path {
  fill: #06070E;
}

#challenger-calendar-modal .activity-score-box {
  width: 100%;
  max-width: 116px;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .activity-score-box {
    display: none;
  }
}

#challenger-calendar-modal .calendar-outer-box {
  width: 100%;
}

@media (min-width: 768px) {
  #challenger-calendar-modal .calendar-outer-box {
    max-width: 357px;
  }
}

#challenger-calendar-modal .score-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 24px;
  letter-spacing: -0.011em;
  text-align: left;
  margin-top: 20px;
}

#challenger-calendar-modal .score-point-list {
  row-gap: 5px;
}

#challenger-calendar-modal .score-point-item.active svg,
#challenger-calendar-modal .score-point-item.active path {
  fill: #690FAD;
}

#challenger-calendar-modal .score-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 18px;
  letter-spacing: -0.011em;
  text-align: left;
}

#challenger-calendar-modal .btn-prev-month,
#challenger-calendar-modal .btn-next-month {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  color: #CAA8F5;
}

#challenger-calendar-modal .btn-prev-month svg,
#challenger-calendar-modal .btn-prev-month path,
#challenger-calendar-modal .btn-next-month svg,
#challenger-calendar-modal .btn-next-month path {
  fill: #CAA8F5;
}

#challenger-calendar-modal .icon-prev {
  margin-right: 10px;
}

#challenger-calendar-modal .icon-next {
  margin-left: 10px;
}

#challenger-calendar-modal .calendar-header .active {
  color: #690FAD;
}

#challenger-calendar-modal .calendar-header .active svg,
#challenger-calendar-modal .calendar-header .active path {
  fill: #690FAD;
}

#challenger-calendar-modal .calendar-body {
  padding-top: 20px;
}

#challenger-calendar-modal .calendar-tr {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  border-bottom: 1px solid #CAA8F5;
  border-right: 1px solid #CAA8F5;
}

#challenger-calendar-modal .calendar-th {
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  font-weight: 600;
  line-height: 13px;
  letter-spacing: 0em;
  text-align: left;
  color: #690FAD;
  padding: 7px 6px;
  height: 43px;
  border-left: 1px solid #CAA8F5;
  padding-right: 0;
}

#challenger-calendar-modal .calendar-td {
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  font-weight: 400;
  line-height: 13px;
  letter-spacing: 0em;
  text-align: left;
  padding: 17px 6px 7px;
  height: 70px;
  border-left: 1px solid #CAA8F5;
  row-gap: 13px;
  color: rgba(0, 0, 0, 0.8);
}

#challenger-calendar-modal .not-selected-month {
  color: rgba(0, 0, 0, 0.45);
}

#challenger-calendar-modal .point-scored {
  color: #690FAD;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .point-scored {
    font-size: 14px;
    line-height: 17px;
  }
}

#challenger-calendar-modal .today {
  background-color: #CAA8F5;
}

#challenger-calendar-modal .activity-stats-list {
  row-gap: 10px;
  -webkit-column-gap: 15px;
  column-gap: 15px;
  margin-bottom: 33px;
}

@media (max-width: 767px) {
  #challenger-calendar-modal .activity-stats-list {
    -webkit-column-gap: 20px;
    column-gap: 20px;
    margin-bottom: 15px;
  }
}

@media (max-width: 389px) {
  #challenger-calendar-modal .activity-stats-list {
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
}

#challenger-calendar-modal .activity-stats-item {
  -webkit-column-gap: 10px;
  column-gap: 10px;
}

@media (max-width: 389px) {
  #challenger-calendar-modal .activity-stats-item {
    width: 105px;
  }
}

#challenger-calendar-modal .activity-label {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 400;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: left;
}

#challenger-calendar-modal .activity-points {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
}
</style>