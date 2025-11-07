<template>
  <div>
    <div class="select-date-range">
      <date-range-picker-header />
      <div class="date-range-box">
        <div class="date-range-outer-box">
          <div class="date-range-inner-box current-month-box">
            <div class="calendar-header">
              <button class="prev-month" @click="prev()">
                <back-arrow-blue-icon />
              </button>

              <p class="selected-month">
                {{ currentMonthName }},{{ currentYear }}
              </p>
              <button v-if="isMobile" class="prev-month" @click="next()">
                <forward-arrow-blue-icon />
              </button>
            </div>
            <div class="calendar-body">
              <section class="flex days-name">
                <p
                  v-for="day in days"
                  :key="'previous-' + day"
                  class="text-center"
                  style="width: 14.28%"
                >
                  {{ day }}
                </p>
              </section>
              <section class="flex flex-wrap month-days">
                <p
                  v-for="previousDates in startDay()"
                  :key="'dayInWeek-' + previousDates"
                  class="text-center from-other-month"
                  style="width: 14.28%; cursor: pointer"
                >
                  {{ previousDates }}
                </p>
                <p
                  v-for="day in daysInMonth()"
                  :key="'leftCalendarDate' + day"
                  :class="[
                    'text-center',
                    'currentMonth',
                    'currentMonthDate',
                    `days-${day}-${currentMonth + 1}-${currentYear}`,
                    fromClass(day, currentMonth + 1, currentYear),
                    toClass(day, currentMonth + 1, currentYear),
                    monthDateHoverClass(day, 'current'),
                    disablePastDate(new Date(currentYear, currentMonth, day)),
                  ]"
                  style="width: 14.28%; font-weight: 700; cursor: pointer"
                  @click="addActiveClass(day, currentMonth + 1, currentYear)"
                  @mouseover="
                    addMouseOverOnDay(day, currentMonth + 1, currentYear)
                  "
                >
                  {{ day }}
                </p>
                <p
                  v-for="remainingDays in remainingDayCurrentMonth()"
                  :key="'remainingDays-' + remainingDays"
                  class="text-center from-other-month"
                  style="width: 14.28%; cursor: pointer"
                >
                  {{ remainingDays }}
                </p>
              </section>
            </div>
          </div>
          <div class="date-range-inner-box next-month-box">
            <div class="calendar-header">
              <p class="selected-month">
                {{ nextMonthName }},{{ nextMonthYear }}
              </p>
              <button class="next-month" @click="next()">
                <forward-arrow-blue-icon />
              </button>
            </div>
            <div class="calendar-body">
              <section class="flex days-name">
                <p
                  v-for="day in days"
                  :key="'next-' + day"
                  class="text-center"
                  style="width: 14.28%"
                >
                  {{ day }}
                </p>
              </section>
              <section class="flex flex-wrap month-days">
                <p
                  v-for="previousDates in startDayOfTheNextMonth()"
                  :key="'dayInWeek-' + previousDates"
                  class="text-center from-other-month"
                  style="width: 14.28%; cursor: pointer"
                >
                  {{ previousDates }}
                </p>
                <p
                  v-for="day in daysInMonth()"
                  :key="'rightCalendarDate' + day"
                  :class="[
                    'text-center',
                    'currentMonth',
                    'nextMonthDate',
                    `days-${day}-${nextMonth + 1}-${nextMonthYear}`,
                    fromClass(day, nextMonth + 1, nextMonthYear),
                    toClass(day, nextMonth + 1, nextMonthYear),
                    monthDateHoverClass(day, 'next'),
                    disablePastDate(new Date(nextMonthYear, nextMonth, day)),
                  ]"
                  style="width: 14.28%; font-weight: 700; cursor: pointer"
                  @click="addActiveClass(day, nextMonth + 1, nextMonthYear)"
                  @mouseover="
                    addMouseOverOnDay(day, nextMonth + 1, nextMonthYear)
                  "
                >
                  {{ day }}
                </p>
                <p
                  v-for="remainingDays in remainingDayNextMonth()"
                  :key="'remainingDays-' + remainingDays"
                  class="text-center from-other-month"
                  style="width: 14.28%; cursor: pointer"
                >
                  {{ remainingDays }}
                </p>
              </section>
            </div>
          </div>
        </div>
        <div v-for="date in dateInterval" :key="date.startDate">
          <div v-if="date.endDate" class="selected-date-box">
            <button
              class="btn-date-from"
              @click="removeDateInterval(date.startDate)"
            >
              <blue-close-icon />
              {{ date.startDate }}
            </button>
            <span class="divider">
              <horizontal-divider-black />
            </span>
            <button class="btn-date-to">
              {{ date.endDate }}
            </button>
          </div>
        </div>
      </div>
    </div>
    <FilterButtons @apply="applyFilter" @reset="resetFilter" />
  </div>
</template>

<script>
import FilterButtons from "@/partials/FilterButtons";
import BackArrowBlueIcon from "../svgs/arrows/back-arrow-blue-icon";
import ForwardArrowBlueIcon from "../svgs/arrows/forward-arrow-blue-icon";
import BlueCloseIcon from "../svgs/blue-close-icon";
import HorizontalDividerBlack from "../svgs/horizontal-divider-black";
import DateRangePickerHeader from "../components/activity-listing-date-picker-widget/date-range-picker-header";
import moment from "moment";
import {changeDateFormat, isEndDateGreaterStartDate} from "@/dateUtils";

export default {
  name: "CalendarFilter",
  components: {
    DateRangePickerHeader,
    HorizontalDividerBlack,
    BlueCloseIcon,
    ForwardArrowBlueIcon,
    BackArrowBlueIcon,
    FilterButtons,
  },
  data() {
    return {
      currentDate: new Date().getUTCDate(),
      currentMonth: new Date().getMonth(),
      nextMonth: new Date().getMonth() + 1,
      currentYear: new Date().getFullYear(),
      nextMonthYear:
          new Date().getMonth() + 1 === 11
            ? new Date().getFullYear() + 1
            : new Date().getFullYear(),
      days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
      dateInterval: [],
      isMobile: false,
      selectedDates: [],
      previousSelectedDateRange: [],
    };
  },
  mounted() {
    this.isMobile = this.checkBrowserViewportHeight();
    this.addHoverClass();
  },
  methods: {
    removeDateInterval(startDate) {
      let startDateIndex = this.dateInterval.findIndex(
        (data) => data.startDate == startDate
      );
      if (startDateIndex !== -1) {
        let dataRanges = this.getDatesInRange(
          new Date(
            moment(
              this.dateInterval[startDateIndex].startDate,
              "DD-MM-YYYY"
            ).format("YYYY-MM-DD")
          ),
          new Date(
            moment(
              this.dateInterval[startDateIndex].endDate,
              "DD-MM-YYYY"
            ).format("YYYY-MM-DD")
          )
        );
        this.selectedDates = this.selectedDates.filter(
          (date) => !dataRanges.includes(date)
        );
      }
      this.dateInterval = this.dateInterval.filter(
        (date) => date.startDate != startDate
      );
      this.addHoverClass();
    },
    checkBrowserViewportHeight() {
      return (
        Math.min(window.screen.width, window.screen.height) < 768 ||
          navigator.userAgent.indexOf("Mobi") > -1
      );
    },
    disablePastDate(date) {
      if (new Date(date.toDateString()) < new Date(new Date().toDateString())) {
        return "from-other-month";
      }
    },
    addActiveClass(day, month, year) {
      if (this.selectedDates.length >= 7) return false;
      if (
        $(".days-" + day + "-" + month + "-" + year).hasClass(
          "from-other-month"
        )
      ) {
        return false;
      }
      let hasEndDate = this.dateInterval.findIndex(
        (data) => data.endDate == ""
      );
      if (hasEndDate !== -1) {
        if (this.selectedDates.includes(`${day}-${month}-${year}`))
          return false;
        let startDate = changeDateFormat(
          this.dateInterval[hasEndDate].startDate,
          "DD-MM-YYYY",
          "YYYY-MM-DD"
        );
        let endDate = changeDateFormat(
          `${day}-${month}-${year}`,
          "DD-MM-YYYY",
          "YYYY-MM-DD"
        );
        if (isEndDateGreaterStartDate(startDate, endDate)) return false;
        let totalSelectedDate = this.getDatesInRange(
          new Date(
            moment(
              this.dateInterval[hasEndDate].startDate,
              "DD-MM-YYYY"
            ).format("YYYY-MM-DD")
          ),
          new Date(
            moment(`${day}-${month}-${year}`, "DD-MM-YYYY").format("YYYY-MM-DD")
          )
        );
        let previousAndCurrentSelectedDates =
            totalSelectedDate.length + this.selectedDates.length;
        if (previousAndCurrentSelectedDates > 7) {
          return false;
        }
        this.dateInterval[hasEndDate].endDate = `${day}-${month}-${year}`;
        this.selectedDates = [...this.selectedDates, ...totalSelectedDate];
        this.previousSelectedDateRange = [];
      } else {
        if (this.selectedDates.includes(`${day}-${month}-${year}`))
          return false;
        this.dateInterval.push({
          startDate: `${day}-${month}-${year}`,
          endDate: "",
        });
      }
    },
    addMouseOverOnDay(day, month, year) {
      if (
        $(".days-" + day + "-" + month + "-" + year).hasClass(
          "from-other-month"
        )
      ) {
        $(".currentMonthDate ").removeClass("currentMonthDateHoverClass");
        return false;
      }
      if (this.selectedDates.includes(`${day}-${month}-${year}`)) return false;
      let hasEmptyEndDate = this.dateInterval.findIndex(
        (data) => data.endDate == ""
      );
      if (hasEmptyEndDate !== -1) {
        let dateFrom = this.dateInterval[hasEmptyEndDate].startDate;
        let getAllDatesFromStartToEnd = this.getDatesInRange(
          new Date(
            moment(
              this.dateInterval[hasEmptyEndDate].startDate,
              "DD-MM-YYYY"
            ).format("YYYY-MM-DD")
          ),
          new Date(
            moment(`${day}-${month}-${year}`, "DD-MM-YYYY").format("YYYY-MM-DD")
          )
        );
        if (this.previousSelectedDateRange.length === 0) {
          this.previousSelectedDateRange = getAllDatesFromStartToEnd;
        }
        if (
          this.previousSelectedDateRange.length <
            getAllDatesFromStartToEnd.length
        ) {
          this.previousSelectedDateRange = getAllDatesFromStartToEnd;
        }
        if (
          this.previousSelectedDateRange.length >
            getAllDatesFromStartToEnd.length
        ) {
          for (
            let previousDate = 0;
            previousDate < this.previousSelectedDateRange.length;
            previousDate++
          ) {
            $(
              ".days-" + this.previousSelectedDateRange[previousDate]
            ).removeClass("currentMonthDateBetweenClass");
          }
        }
        let dateTo = `${day}-${month}-${year}`;
        if (dateFrom.toString() == dateTo.toString()) {
          return false;
        }
        for (let missingDate = 0; missingDate <= 7; missingDate++) {
          $(".days-" + getAllDatesFromStartToEnd[missingDate]).addClass(
            "currentMonthDateBetweenClass"
          );
        }
      } else {
        $(".currentMonthDate , .nextMonthDate").removeClass(
          "currentMonthDateHoverClass"
        );
        document
          .querySelector(".days-" + day + "-" + month + "-" + year)
          .classList.add("currentMonthDateHoverClass");
        return false;
      }
    },
    splitDate(date, splitDateBy, addMonth = false) {
      let dateFromSplit = date.split("-");
      let dateFromSplitDay = parseInt(dateFromSplit[0]);
      let dateFromSplitMonth = parseInt(dateFromSplit[1]);
      let dateFromSplitYear = parseInt(dateFromSplit[2]);
      if (splitDateBy === "month") {
        return (
          dateFromSplitMonth + "-" + dateFromSplitDay + "-" + dateFromSplitYear
        );
      } else if (splitDateBy === "year") {
        if (addMonth) {
          dateFromSplitMonth++;
          return (
            dateFromSplitYear +
              "-" +
              dateFromSplitMonth +
              "-" +
              dateFromSplitDay
          );
        }
        return (
          dateFromSplitYear + "-" + dateFromSplitMonth + "-" + dateFromSplitDay
        );
      }
    },
    daysInMonth() {
      return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
    },
    daysInNextMonth() {
      return new Date(this.nextMonthYear, this.nextMonth + 1, 0).getDate();
    },
    startDay() {
      let previousMonthDates = [];
      let previousMonthLastDate = new Date(
        this.currentYear,
        this.currentMonth,
        0
      ).getDate();
      let currentDateStartFrom = new Date(
        this.currentYear,
        this.currentMonth,
        1
      ).getDay();
      for (
        let startDate = previousMonthLastDate - currentDateStartFrom;
        startDate < previousMonthLastDate;
        startDate++
      ) {
        previousMonthDates.push(startDate);
      }
      return previousMonthDates;
    },
    startDayOfTheNextMonth() {
      let previousMonthDates = [];
      let previousMonthLastDate = new Date(
        this.nextMonthYear,
        this.nextMonth,
        0
      ).getDate();
      let currentDateStartFrom = new Date(
        this.nextMonthYear,
        this.nextMonth,
        1
      ).getDay();
      for (
        let startDate = previousMonthLastDate - currentDateStartFrom;
        startDate <= previousMonthLastDate;
        startDate++
      ) {
        previousMonthDates.push(startDate);
      }
      return previousMonthDates;
    },
    remainingDayCurrentMonth() {
      let totalDaysInOneWeek = 7;
      let nextMonthDays =
          new Date(this.currentYear, this.currentMonth + 1, 1).getDay() + 1;
      let remainingDayCurrentMonth = totalDaysInOneWeek - nextMonthDays;
      return remainingDayCurrentMonth;
    },
    remainingDayNextMonth() {
      let totalDaysInOneWeek = 7;
      let nextMonthDays =
          new Date(this.nextMonthYear, this.nextMonth + 1, 1).getDay() + 1;
      let remainingDayCurrentMonth = totalDaysInOneWeek - nextMonthDays;
      return remainingDayCurrentMonth;
    },
    next() {
      if (this.currentMonth === 10 && !this.isMobile) {
        this.currentMonth = 0;
        this.currentYear++;
      } else if (this.currentMonth === 11) {
        if (this.isMobile) {
          this.currentMonth = 0;
          this.currentYear++;
        } else {
          this.currentMonth = 1;
          this.currentYear++;
        }
      } else {
        this.currentMonth = !this.isMobile
          ? this.currentMonth + 2
          : this.currentMonth + 1;
      }
      if (this.nextMonth === 10) {
        this.nextMonth = 0;
        this.nextMonthYear++;
      } else if (this.nextMonth === 11) {
        this.nextMonth = 1;
        this.nextMonthYear++;
      } else {
        this.nextMonth = !this.isMobile
          ? this.nextMonth + 2
          : this.nextMonth + 1;
      }
    },
    prev() {
      if (this.currentMonth === 1 && !this.isMobile) {
        this.currentMonth = 11;
        this.currentYear--;
      } else if (this.currentMonth === 0) {
        this.currentMonth = 11;
        this.currentYear--;
      } else {
        this.currentMonth = !this.isMobile
          ? this.currentMonth - 2
          : this.currentMonth - 1;
      }
      if (this.nextMonth === 0) {
        this.nextMonth = 11;
        this.nextMonthYear--;
      } else {
        this.nextMonth = !this.isMobile
          ? this.nextMonth - 2
          : this.nextMonth - 1;
      }
    },
    fromClass(day, month, year) {
      let dateObject = this.dateInterval.find((date) => {
        if (date.startDate === `${day}-${month}-${year}`) return true;
      });
      return dateObject ? "from" : "";
    },
    toClass(day, month, year) {
      let dateObject = this.dateInterval.find((date) => {
        if (date.endDate == `${day}-${month}-${year}`) return true;
      });
      return dateObject ? "to" : "";
    },
    monthDateHoverClass(day, monthType) {
      let dateCheck =
          monthType === "current"
            ? day + "-" + `${this.currentMonth + 1}` + "-" + this.currentYear
            : day + "-" + `${this.nextMonth + 1}` + "-" + this.nextMonthYear;
      if (this.dateInterval.includes(dateCheck)) return false;
      if (!this.selectedDates.includes(dateCheck)) return false;
      return "currentMonthDateBetweenClass";
    },
    addHoverClass() {
      $("p").removeClass("currentMonthDateBetweenClass");
      const validDateInterval = this.dateInterval.filter(
        (date) => date.endDate
      );
      validDateInterval.forEach((data) => {
        let missingDates = this.getDatesInRange(
          new Date(moment(data.startDate, "DD-MM-YYYY").format("YYYY-MM-DD")),
          new Date(moment(data.endDate, "DD-MM-YYYY").format("YYYY-MM-DD"))
        );
        for (let i = 1; i < missingDates.length - 1; i++) {
          $(".days-" + missingDates[i]).addClass(
            "currentMonthDateBetweenClass"
          );
        }
      });
    },
    getDateDifference(startDate, endDate) {
      const date1 = new Date(startDate);
      const date2 = new Date(endDate);
      const diffTime = Math.abs(date2 - date1);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    },
    resetFilter(value) {
      this.dateInterval = [];
      this.selectedDates = [];
      this.$emit("resetFilter", "Reset Filter");
    },
    applyFilter(value) {
      if (this.dateInterval.length > 0) {
        this.$emit("changed", this.dateInterval);
      }
      this.$emit("applyFilter", "Filter Apply");
    },
    getDatesInRange(startDate, endDate) {
      const date = new Date(startDate.getTime());
      const dates = [];
      while (date <= endDate) {
        dates.push(moment(new Date(date)).format("D-M-YYYY"));
        date.setDate(date.getDate() + 1);
      }
      return dates;
    },
  },
  computed: {
    currentMonthName() {
      return new Date(this.currentYear, this.currentMonth).toLocaleString(
        "default",
        {month: "long"}
      );
    },
    nextMonthName() {
      return new Date(this.currentYear, this.currentMonth + 1).toLocaleString(
        "default",
        {month: "long"}
      );
    },
  },
};
</script>

<style scoped>
@media screen and (min-width: 992px) {
  #advance-filter .advance-filter-btn-box {
    margin-top: 100px;
  }
}

#advance-filter .select-date-range-header {
  margin-bottom: 30px;
}

.select-date-range .title-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

@media screen and (min-width: 992px) {
  .select-date-range .title-box {
    padding-left: 9px;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
  }
}

.select-date-range .select-date-range-desc {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

.select-date-range .select-date-range-desc svg {
  min-width: 20px;
  margin-right: 15px;
}

.select-date-range table {
  width: 100%;
}

.days-name p {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color: #06070e73;
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-style: normal;
  font-weight: 700;
  line-height: 38px;
  letter-spacing: 0em;
  text-align: center;
  height: 38px;
}

.select-date-range th {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  color: #06070e73;
}

.select-date-range td,
.select-date-range th,
.select-date-range p.text-center {
  font-family: "Montserrat", sans-serif;
  font-size: 12px !important;
  font-style: normal;
  font-weight: 700 !important;
  line-height: 12px !important;
  letter-spacing: 0em !important;
  text-align: center !important;
  width: 40px;
  height: 38px !important;
  display: flex;
  align-items: center;
  justify-content: center;
}

@media screen and (min-width: 992px) {
  .select-date-range td,
  .select-date-range th,
  .select-date-range p.text-center {
    font-size: 18px !important;
    line-height: 22px !important;
    width: 60px !important;
    height: 57px !important;
  }

  .days-name p {
    font-size: 18px;
    line-height: 45px;
    height: 45px;
  }

  .month-days p {
    font-size: 18px;
    line-height: 45px;
    height: 45px;
  }
}

.select-date-range td {
  cursor: pointer;
}

.select-date-range td:hover {
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
  color: #ffffff;
  background-color: #690fad;
}

.select-date-range td.active {
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
  color: #ffffff;
  background-color: #690fad;
}

.select-date-range td.from,
.select-date-range td.to {
  color: #ffffff;
  background-color: #690fad;
}

.select-date-range td.from {
  border-radius: 8px 0 0 8px;
  -webkit-border-radius: 8px 0 0 8px;
  -moz-border-radius: 8px 0 0 8px;
  -ms-border-radius: 8px 0 0 8px;
  -o-border-radius: 8px 0 0 8px;
}

.select-date-range td.to {
  border-radius: 0 8px 8px 0;
  -webkit-border-radius: 0 8px 8px 0;
  -moz-border-radius: 0 8px 8px 0;
  -ms-border-radius: 0 8px 8px 0;
  -o-border-radius: 0 8px 8px 0;
}

p.text-center.from-other-month {
  color: #06070e73 !important;
  font-weight: 500 !important;
}

p.text-center.from-other-month:hover {
  color: #06070e73 !important;
  background-color: transparent !important;
}

.select-date-range td.between {
  color: #ffffff;
  background-color: #caa8f5;
}

.select-date-range td.between:hover {
  border-radius: unset;
  -webkit-border-radius: unset;
  -moz-border-radius: unset;
  -ms-border-radius: unset;
  -o-border-radius: unset;
}

.select-date-range .calendar-header {
  width: 100%;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.select-date-range .selected-month {
  width: 100%;
  font-family: "Montserrat", sans-serif;
  font-size: 15px;
  font-style: normal;
  font-weight: 700;
  line-height: 18px;
  letter-spacing: 0em;
  text-align: center;
}

@media screen and (min-width: 992px) {
  .select-date-range .selected-month {
    font-size: 18px;
    line-height: 22px;
  }
}

.select-date-range .calendar-header {
  margin-bottom: 25px;
}

@media screen and (min-width: 992px) {
  .select-date-range .calendar-header {
    margin-bottom: 30px;
  }
}

.select-date-range .prev-month,
.select-date-range .next-month {
  min-width: 32px;
  min-height: 32px;
  max-width: 32px;
  max-height: 32px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background-color: #ffffff;
  -webkit-box-shadow: 0px 1px 4px 0px #00000040;
  box-shadow: 0px 1px 4px 0px #00000040;
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
}

@media screen and (min-width: 992px) {
  .select-date-range .prev-month,
  .select-date-range .next-month {
    min-width: 60px;
    min-height: 60px;
    max-width: 60px;
    max-height: 60px;
  }
}

.select-date-range .date-range-box {
  background-color: #ffffff;
  -webkit-box-shadow: 0px 22px 40px 0px #0000001a;
  box-shadow: 0px 22px 40px 0px #0000001a;
}

.select-date-range .date-range-outer-box {
  width: 100%;
  padding-top: 31px;
  padding-bottom: 31px;
  display: grid;
}

@media screen and (min-width: 768px) {
  .select-date-range .date-range-outer-box {
    grid-template-columns: 1fr 1fr;
    padding: 31px 15px 65px;
    position: relative;
  }

  .select-date-range .date-range-outer-box::after {
    position: absolute;
    content: "";
    width: 2px;
    height: 70%;
    background: #caa8f5;
    left: 50%;
    top: 60px;
  }
}

.select-date-range .date-range-inner-box {
  width: 100%;
  max-width: 100%;
  padding-left: 15px;
  padding-right: 15px;
  margin-left: auto;
  margin-right: auto;
}

@media screen and (min-width: 992px) {
  .select-date-range .date-range-inner-box {
    max-width: calc(420px + 30px);
  }
}
@media screen and (max-width: 480px) {
  .select-date-range .date-range-inner-box {
    max-width: 100%;
    margin: 0;
  }
}

@media screen and (max-width: 767px) {
  .select-date-range .next-month-box {
    display: none;
  }
}

.select-date-range .selected-date-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  width: 100%;
  max-width: 495px;
  margin-left: auto;
  margin-right: auto;
  padding-bottom: 30px;
  padding-left: 15px;
  padding-right: 15px;
}

@media screen and (max-width: 991px) {
  .select-date-range .selected-date-box {
    padding-bottom: 66px;
    max-width: 360px;
  }
}

.days-name p {
  font-size: 12px;
  line-height: 45px;
  height: 45px;
}

.month-days p {
  font-size: 12px;
  line-height: 30px;
  height: 30px;
}

.select-date-range .btn-date-from,
.select-date-range .btn-date-to {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: "Montserrat", sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
}

.select-date-range .btn-date-from svg,
.select-date-range .btn-date-to svg {
  margin-right: 19px;
}

@media screen and (max-width: 991px) {
  .select-date-range .btn-date-from svg,
  .select-date-range .btn-date-to svg {
    margin-right: 5px;
    max-width: 18px;
  }
}

@media screen and (max-width: 991px) {
  .select-date-range .btn-date-from,
  .select-date-range .btn-date-to {
    font-size: 12px;
    line-height: 14px;
  }
}

.currentMonthDateHoverClass {
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
  color: #ffffff;
  background-color: #690fad;
}

.currentMonthDateBetweenClass {
  color: #ffffff;
  background-color: #caa8f5;
  font-size: 18px;
  line-height: 45px;
  height: 45px;
}

.from {
  color: #ffffff;
  background-color: #690fad;
  border-radius: 8px 0 0 8px;
  -webkit-border-radius: 8px 0 0 8px;
  -moz-border-radius: 8px 0 0 8px;
  -ms-border-radius: 8px 0 0 8px;
  -o-border-radius: 8px 0 0 8px;
  line-height: 45px;
  height: 45px;
}

.to {
  line-height: 45px;
  height: 45px;
  color: #ffffff;
  background-color: #690fad;
  border-radius: 0 8px 8px 0;
  -webkit-border-radius: 0 8px 8px 0;
  -moz-border-radius: 0 8px 8px 0;
  -ms-border-radius: 0 8px 8px 0;
  -o-border-radius: 0 8px 8px 0;
}

@media screen and (max-width: 991px) {
  .btn-date-reset-outer-box {
    width: 100%;
    max-width: 106px;
  }

  #advance-filter .select-date-range-header .select-date-range-title {
    font-size: 16px;
    margin-right: 10px;
  }

  #advance-filter .select-date-range-header svg {
    max-width: 18px;
    min-width: 18px;
    margin-right: 5px;
  }

  .select-date-range .select-date-range-desc {
    font-size: 12px;
  }
}

@media screen and (min-width: 992px) {
  .btn-date-reset-outer-box {
    width: 100%;
    max-width: 167px;
  }
}

.from-other-month {
  font-weight: 500 !important;
}
</style>