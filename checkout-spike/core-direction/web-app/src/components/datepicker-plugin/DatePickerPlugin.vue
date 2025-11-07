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
            <activity-calendar :days="days" :previous-dates="startDay()" :total-days-in-month="daysInMonth()" :remaining-days="remainingDayCurrentMonth()" :month="currentMonth" :year="currentYear" />
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
            <activity-calendar :days="days" :previous-dates="startDayOfTheNextMonth()" :total-days-in-month="daysInNextMonth()" :month="nextMonth" :year="nextMonthYear" :remaining-days="remainingDayNextMonth()" />
          </div>
        </div>
        <div class="selected-date-box">
          <button v-if="dateInterval.startDate" class="btn-date-from" @click="removeStartDate">
            <blue-close-icon />
            {{ startDateFormat }}
          </button>
          <span v-if="dateInterval.endDate" class="divider">
            <horizontal-divider-black />
          </span>
          <button v-if="dateInterval.endDate" class="btn-date-to" @click="removeEndDate">
            <blue-close-icon />
            {{ endDateFormat }}
          </button>
        </div>
      </div>
    </div>
    <FilterButtons @reset="resetFilter" @apply="applyFilter" />
  </div>
</template>

<script>
import FilterButtons from "@/partials/FilterButtons";
import BackArrowBlueIcon from "@/svgs/arrows/back-arrow-blue-icon";
import ForwardArrowBlueIcon from "@/svgs/arrows/forward-arrow-blue-icon";
import BlueCloseIcon from "@/svgs/blue-close-icon";
import HorizontalDividerBlack from "@/svgs/horizontal-divider-black";
import DateRangePickerHeader from "@/components/activity-listing-date-picker-widget/date-range-picker-header";
import ActivityCalendar from "@/components/datepicker-plugin/activity-calendar";
export default {
    name:"DatePickerPlugin",
    components: {
        ActivityCalendar,
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
            nextMonth:new Date().getMonth() + 1,
            currentYear: new Date().getFullYear(),
            nextMonthYear:(new Date().getMonth() + 1) === 11 ? new Date().getFullYear() + 1 : new Date().getFullYear(),
            days: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dateInterval: {
                startDate:"",
                endDate:""
            },
            isMobile:false
        };
    },
    mounted(){
        this.isMobile = this.checkBrowserViewportHeight();
    },
    methods: {
        removeStartDate(){
            this.dateInterval.startDate = "";
            $(".days").removeClass("from");
        },
        removeEndDate(){
            this.dateInterval.endDate = "";
            $(".days").removeClass("to");
        },
        checkBrowserViewportHeight(){
            return Math.min(window.screen.width, window.screen.height) < 768 || navigator.userAgent.indexOf("Mobi") > -1;
        },
        addBackgroundClass(className){
            let splitDate = className.split("-");
            let date = splitDate[1];
            this.dateInterval.startDate = date;
            document.getElementById(className).classList.add("active-class");

        },
        disablePastDate(date){
            if(new Date(date.toDateString()) < new Date(new Date().toDateString()))
            {
                return "from-other-month";
            }
        },

        daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },
        daysInNextMonth() {
            return new Date(this.nextMonthYear, this.nextMonth + 1, 0).getDate();
        },
        // startDay() {
        //     let previousMonthDates = [];
        //     let previousMonthLastDate = new Date(this.currentYear, this.currentMonth, 0).getDate();
        //     let currentDateStartFrom = new Date(this.currentYear, this.currentMonth, 1).getDay();
        //     for(let startDate = previousMonthLastDate - currentDateStartFrom ; startDate <= previousMonthLastDate; startDate ++){
        //         previousMonthDates.push(startDate);
        //     }
        //     return previousMonthDates;
        //
        // },
        startDay() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },
        startDayOfTheNextMonth() {
            return new Date(this.currentYear, this.currentMonth, 1).getDay();
        },
        // startDayOfTheNextMonth() {
        //     let previousMonthDates = [];
        //     let previousMonthLastDate = new Date(this.nextMonthYear, this.nextMonth, 0).getDate();
        //     let currentDateStartFrom = new Date(this.nextMonthYear, this.nextMonth, 1).getDay();
        //     for(let startDate = previousMonthLastDate - currentDateStartFrom ; startDate <= previousMonthLastDate; startDate ++){
        //         previousMonthDates.push(startDate);
        //     }
        //     return previousMonthDates;
        // },
        remainingDayCurrentMonth(){
            let totalDaysInOneWeek = 7;
            let nextMonthDays = new Date(this.currentYear, this.currentMonth + 1, 1).getDay() + 1;
            let remainingDayCurrentMonth = totalDaysInOneWeek - nextMonthDays;
            return remainingDayCurrentMonth;
        },
        remainingDayNextMonth(){
            let totalDaysInOneWeek = 7;
            let nextMonthDays = new Date(this.nextMonthYear, this.nextMonth + 1, 1).getDay() + 1;
            let remainingDayCurrentMonth = totalDaysInOneWeek - nextMonthDays;
            return remainingDayCurrentMonth;
        },
        next() {
            if(this.currentMonth === 10 && !this.isMobile){
                this.currentMonth  = 0;
                this.currentYear++;
            } else if (this.currentMonth === 11 ) {
                if(this.isMobile){
                    this.currentMonth = 0 ;
                    this.currentYear++;
                } else {
                    this.currentMonth = 1 ;
                    this.currentYear++;
                }

            } else {
                this.currentMonth = !this.isMobile ?  this.currentMonth + 2 :  this.currentMonth + 1;
            }

            if(this.nextMonth === 10){
                this.nextMonth  = 0;
                this.nextMonthYear++;
            } else if (this.nextMonth === 11 ) {
                this.nextMonth = 1;
                this.nextMonthYear++;
            } else {
                this.nextMonth = !this.isMobile ?  this.nextMonth + 2 :  this.nextMonth + 1;
            }

        },
        prev() {
            if(this.currentMonth === 1 && !this.isMobile){
                this.currentMonth = 11;
                this.currentYear --;
            }else if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth = !this.isMobile ?  this.currentMonth - 2 :  this.currentMonth - 1;
            }

            if (this.nextMonth === 0) {
                this.nextMonth = 11;
                this.nextMonthYear--;
            } else {
                this.nextMonth = !this.isMobile ?  this.nextMonth - 2 :  this.nextMonth - 1;
            }
        },
        fromClass(day,month,year) {
            return this.dateInterval.startDate == day+"-"+month +"-"+year  ? "from" :"";
        },
        toClass(day,month,year){
            return this.dateInterval.endDate == day+"-"+month +"-"+year ? "to" :"";
        },
        monthDateHoverClass(day,monthType){
            if(this.dateInterval.startDate && this.dateInterval.endDate){
                let dateFrom = this.dateInterval.startDate;
                let dateTo = this.dateInterval.endDate;
                let dateCheck = monthType === "current" ?  day+"-"+this.currentMonth +"-"+this.currentYear : day+"-"+this.nextMonth +"-"+this.nextMonthYear ;
                if(dateFrom.toString() == dateCheck.toString() || dateTo.toString() == dateCheck.toString()){
                    return false;
                }
                let dateFromSplit = dateFrom.split("-");
                let dateToSplit = dateTo.split("-");
                let c = dateCheck.split("-");

                let from = new Date(dateFromSplit[2], parseInt(dateFromSplit[1]), dateFromSplit[0]);
                let to   = new Date(dateToSplit[2], parseInt(dateToSplit[1]), dateToSplit[0]);
                let check = new Date(c[2], parseInt(c[1]), c[0]);

                if(check > from && check < to){
                    return "currentMonthDateBetweenClass";
                }
            }

        },

        resetFilter(value){
            this.dateInterval.startDate = "";
            this.dateInterval.endDate = "";
            this.$emit("resetFilter","Reset Filter");
        },
        applyFilter(value){
            if(this.dateInterval.startDate && this.dateInterval.endDate){
                let startDate = this.splitDate(this.dateInterval.startDate,"year",true);
                let endDate = this.splitDate(this.dateInterval.endDate,"year",true);
                this.$emit("changed",[startDate,endDate]);
            }

            this.$emit("applyFilter","Filter Apply");
        }
    },
    computed: {
        currentMonthName() {
            return new Date(
                this.currentYear,
                this.currentMonth
            ).toLocaleString("default", { month: "long" });
        },
        nextMonthName() {
            return new Date(
                this.currentYear,
                this.currentMonth + 1
            ).toLocaleString("default", { month: "long" });
        },
        startDateFormat: function () {
            let splitStartDate = this.dateInterval.startDate.split("-");
            let today = new Date(splitStartDate[2],splitStartDate[1],splitStartDate[0]);
            let startDateFormat =  today.toLocaleDateString("en-us", {  month:"long", year:"numeric", day:"numeric"});
            return startDateFormat;
        },
        endDateFormat: function () {
            let splitEndDate = this.dateInterval.endDate.split("-");
            let today = new Date(splitEndDate[2],splitEndDate[1],splitEndDate[0]);
            let endDateFormat =  today.toLocaleDateString("en-us", { month:"long", year:"numeric",  day:"numeric"});
            return endDateFormat;
        }
    },
};
</script>

<style>
@media screen and (min-width: 992px){
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
  font-family: 'Montserrat', sans-serif;
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
  color: #06070E73;
  font-family: 'Montserrat', sans-serif;
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
  color: #06070E73;
}
.select-date-range td,
.select-date-range th,
.select-date-range p.text-center {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px !important;
  font-style: normal;
  font-weight: 700 !important;
  line-height: 12px !important;
  letter-spacing: 0em !important;
  text-align: center !important;
  width: 40px !important;
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
    line-height: 45PX;
    height: 45px;
  }
  .month-days p {
    font-size: 18px;
    line-height: 45PX;
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
  color: #FFFFFF;
  background-color: #690FAD;
}
.select-date-range td.active {
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
  color: #FFFFFF;
  background-color: #690FAD;
}
.select-date-range td.from, .select-date-range td.to {
  color: #FFFFFF;
  background-color: #690FAD;
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
  color: #06070E73 !important;
  font-weight: 500 !important;
}
p.text-center.from-other-month:hover {
  color: #06070E73 !important;
  background-color: transparent !important;
}
.select-date-range td.between {
  color: #FFFFFF;
  background-color: #CAA8F5;
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
  font-family: 'Montserrat', sans-serif;
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
.select-date-range .prev-month, .select-date-range .next-month {
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
  background-color: #FFFFFF;
  -webkit-box-shadow: 0px 1px 4px 0px #00000040;
  box-shadow: 0px 1px 4px 0px #00000040;
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
}
@media screen and (min-width: 992px) {
  .select-date-range .prev-month, .select-date-range .next-month {
    min-width: 60px;
    min-height: 60px;
    max-width: 60px;
    max-height: 60px;
  }
}
.select-date-range .date-range-box {
  background-color: #FFFFFF;
  -webkit-box-shadow: 0px 22px 40px 0px #0000001A;
  box-shadow: 0px 22px 40px 0px #0000001A;
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
    background: #CAA8F5;
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
@media screen and (max-width: 767px) {
  .select-date-range .next-month-box {
    display: none;
  }
}
@media screen and (max-width: 480px) {
  .select-date-range .date-range-inner-box {
    max-width: calc(100% - 23px);
    margin: 0;
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
  line-height: 45PX;
  height: 45px;
}
.month-days p {
  font-size: 12px;
  line-height: 30PX;
  height: 30px;
}
.select-date-range .btn-date-from, .select-date-range .btn-date-to {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-style: normal;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
}
.select-date-range .btn-date-from svg, .select-date-range .btn-date-to svg {
  margin-right: 19px;
}
@media screen and (max-width: 991px) {
  .select-date-range .btn-date-from svg, .select-date-range .btn-date-to svg {
    margin-right: 5px;
    max-width: 18px;
  }
}
@media screen and (max-width: 991px) {
  .select-date-range .btn-date-from, .select-date-range .btn-date-to {
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
  color: #FFFFFF;
  background-color: #690FAD;
}
.currentMonthDateBetweenClass {
  color: #FFFFFF;
  background-color: #CAA8F5;
  font-size: 18px;
  line-height: 45PX;
  height: 45px;
}

.from {
  color: #FFFFFF;
  background-color: #690FAD;
  border-radius: 8px 0 0 8px;
  -webkit-border-radius: 8px 0 0 8px;
  -moz-border-radius: 8px 0 0 8px;
  -ms-border-radius: 8px 0 0 8px;
  -o-border-radius: 8px 0 0 8px;
  line-height: 45PX;
  height: 45px;
}
.to {
  line-height: 45PX;
  height: 45px;
  color: #FFFFFF;
  background-color: #690FAD;
  border-radius: 0 8px 8px 0;
  -webkit-border-radius: 0 8px 8px 0;
  -moz-border-radius: 0 8px 8px 0;
  -ms-border-radius: 0 8px 8px 0;
  -o-border-radius: 0 8px 8px 0;
}

@media screen and (max-width: 991px){
  .btn-date-reset-outer-box{
    width:100%;
    max-width:106px;
  }
  #advance-filter .select-date-range-header .select-date-range-title{
    font-size: 16px;
    margin-right: 10px;
  }
  #advance-filter .select-date-range-header svg{
    max-width: 18px;
    min-width: 18px;
    margin-right: 5px;
  }
  .select-date-range .select-date-range-desc{
    font-size: 12px;
  }
}
@media screen and (min-width: 992px){
  .btn-date-reset-outer-box{
    width:100%;
    max-width:167px;
  }
}
.from-other-month{
  font-weight: 500 !important;
}
</style>