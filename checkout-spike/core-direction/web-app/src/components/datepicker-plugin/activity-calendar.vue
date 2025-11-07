<template>
  <div class="calendar-body">
    <section class="flex days-name">
      <p
        v-for="day in days"
        :key="day"
        class="text-center"
        style="width: 14.28%;"
      >
        {{ day }}
      </p>
    </section>
    <section class="flex flex-wrap month-days">
      <p
        v-for="previousDate in previousDates"
        :key="'dayInWeek-'+previousDate"
        class="text-center from-other-month"
        style="width: 14.28%;cursor:pointer;"
      >
        {{ previousDate }}
      </p>
      <p
        v-for="date in totalDaysInMonth"
        :key="'rightCalendarDate' + date"
        :class="['text-center','currentMonth','nextMonthDate', 'days'+'-'+date+'-'+month+'-'+year,fromClass(date,month,year),toClass(date,month,year),monthDateHoverClass(date,'next'),addClassDisableOnPastDate(date)]"
        style="width: 14.28%;font-weight: 700;cursor: pointer;"
        @click="addActiveClass(date,month,year)"
        @mouseover="addMouseOverOnDay(date,month,year)"
      >
        {{ date }}
      </p>
      <p
        v-for="remainingDay in remainingDays"
        :key="'remainingDays-'+remainingDay"
        class="text-center from-other-month"
        style="width: 14.28%;cursor:pointer;"
      >
        {{ remainingDay }}
      </p>
    </section>
  </div>
</template>

<script>
import moment from "moment";

export default {
    name: "ActivityCalendar",
    props: {
        days: {
            type: Array,
            required: true
        },
        previousDates: {
            type: Number,
            required: true
        },
        totalDaysInMonth: {
            type: Number,
            required: true
        },
        month: {
            type: Number,
            required: true
        },
        year: {
            type: Number,
            required: true
        },
        remainingDays: {
            type: Number,
            required: true
        },

    },
    data() {
        return {
            dateInterval: {
                startDate: "",
                endDate: ""
            },
            isoDateString: null,
        };
    },

    methods: {
        fromClass(day, month, year) {
            return this.dateInterval.startDate == day + "-" + month + "-" + year ? "from" : "";
        },
        toClass(day, month, year) {
            return this.dateInterval.endDate == day + "-" + month + "-" + year ? "to" : "";
        },
        monthDateHoverClass(day, monthType) {
            if (this.dateInterval.startDate && this.dateInterval.endDate) {
                let dateFrom = this.dateInterval.startDate;
                let dateTo = this.dateInterval.endDate;
                let dateCheck = monthType === "current" ? day + "-" + month + "-" + year : day + "-" + this.nextMonth + "-" + this.nextMonthYear;
                if (dateFrom.toString() == dateCheck.toString() || dateTo.toString() == dateCheck.toString()) {
                    return false;
                }
                let dateFromSplit = dateFrom.split("-");
                let dateToSplit = dateTo.split("-");
                let c = dateCheck.split("-");

                let from = new Date(dateFromSplit[2], parseInt(dateFromSplit[1]), dateFromSplit[0]);
                let to = new Date(dateToSplit[2], parseInt(dateToSplit[1]), dateToSplit[0]);
                let check = new Date(c[2], parseInt(c[1]), c[0]);

                if (check > from && check < to) {
                    return "currentMonthDateBetweenClass";
                }
            }
        },
        addClassDisableOnPastDate(date) {
            return moment().isBefore(this.dateInHyphenFormat(date)) ? "from-other-month" : ""; // true
        },
        dateInHyphenFormat(date) {
            return `${this.year}-${this.month}-${date}`;
        },
        addMouseOverOnDay(day, month, year) {
            if ($(".days-" + day + "-" + month + "-" + year).hasClass("from-other-month")) {
                $(".currentMonthDate ").removeClass("currentMonthDateHoverClass");
                return false;
            }
            if (this.dateInterval.startDate && !this.dateInterval.endDate) {
                $("p.currentMonth").removeClass("currentMonthDateBetweenClass");
                let dateFrom = this.dateInterval.startDate;
                let dateTo = day + "-" + month + "-" + year;
                if (dateFrom.toString() == dateTo.toString()) {
                    return false;
                }
                let missingDatesArray = this.findMissingDate(dateFrom, year, month, day, "end");
                for (let missingDate = 0; missingDate < 6; missingDate++) {
                    $(".days-" + missingDatesArray[missingDate]).addClass("currentMonthDateBetweenClass");
                }
            } else if (!this.dateInterval.startDate && this.dateInterval.endDate) {
                $("p.currentMonth").removeClass("currentMonthDateBetweenClass");
                let dateTo = this.dateInterval.endDate;
                let splitEndDate = this.dateInterval.endDate.split("-");
                if (new Date(splitEndDate[2], splitEndDate[1], splitEndDate[0]) < new Date(year, month, day)) {
                    return false;
                }
                let hoverDate = day + "-" + month + "-" + year;
                let missingDatesArray = this.findMissingDate(hoverDate, splitEndDate[2], splitEndDate[1], splitEndDate[0], "start");
                let missingDateLength = 0;
                for (let missingDate = missingDatesArray.length; missingDate >= 0; missingDate--) {
                    if (missingDateLength < 7) {
                        $(".days-" + missingDatesArray[missingDate]).addClass("currentMonthDateBetweenClass");
                    }
                    missingDateLength++;

                }
            }
            if (!this.dateInterval.startDate && !this.dateInterval.endDate) {
                $(".currentMonthDate , .nextMonthDate").removeClass("currentMonthDateHoverClass");
                document.querySelector(".days-" + day + "-" + month + "-" + year).classList.add("currentMonthDateHoverClass");
                return false;
            }

        },
        addActiveClass(day, month, year) {
            if ($(".days-" + day + "-" + month + "-" + year).hasClass("from-other-month")) {
                return false;
            }
            if (this.dateInterval.startDate && this.dateInterval.endDate) {
                $(".days").removeClass("currentMonthDateHoverClass");
                this.dateInterval.startDate = "";
                this.dateInterval.endDate = "";
            }
            if (!this.dateInterval.startDate) {
                if (this.dateInterval.endDate) {
                    let splitEndDate = this.dateInterval.endDate.split("-");
                    if (new Date(splitEndDate[2], splitEndDate[1], splitEndDate[0]) < new Date(year, month, day)) {
                        return false;
                    }
                }
                this.dateInterval.startDate = day + "-" + month + "-" + year;

            } else {
                let splitStartDate = this.dateInterval.startDate.split("-");
                if (this.getDateDifference(this.splitDate(this.dateInterval.startDate, "month"), month + "-" + day + "-" + year) >= 7) {
                    return false;
                } else if (new Date(year, month, day) < new Date(splitStartDate[2], splitStartDate[1], splitStartDate[0])) {
                    return false;
                }
                document.querySelector(".days-" + day + "-" + month + "-" + year).classList.add("currentMonthDateHoverClass");
                this.dateInterval.endDate = day + "-" + month + "-" + year;
            }


        },
        findMissingDate(startDate, endDateYear, endDateMonth, endDateDay, dateType) {
            let startDateSplit = startDate.split("-");
            let dates = [new Date(startDateSplit[2], startDateSplit[1], startDateSplit[0]), new Date(endDateYear, endDateMonth, endDateDay)];
            let missingDatesArray = [];
            for (var i = 1; i <= dates.length; i++) {
                var daysDiff = ((dates[i] - dates[i - 1]) / 86400000) - 1;
                for (var j = 1; j <= daysDiff; j++) {
                    var missingDate = new Date(dates[i - 1]);
                    missingDate.setDate(dates[i - 1].getDate() + j);
                    missingDatesArray.push(missingDate.getDate() + "-" + missingDate.getMonth() + "-" + missingDate.getFullYear());
                }
            }
            if (dateType == "end") {
                missingDatesArray.push(endDateDay + "-" + endDateMonth + "-" + endDateYear);
            } else {
                missingDatesArray.push(startDateSplit[0] + "-" + startDateSplit[1] + "-" + startDateSplit[2]);
            }

            return missingDatesArray;

        },
        splitDate(date, splitDateBy, addMonth = false) {
            let dateFromSplit = date.split("-");
            let dateFromSplitDay = parseInt(dateFromSplit[0]);
            let dateFromSplitMonth = parseInt(dateFromSplit[1]);
            let dateFromSplitYear = parseInt(dateFromSplit[2]);

            if (splitDateBy == "month") {
                return dateFromSplitMonth + "-" + dateFromSplitDay + "-" + dateFromSplitYear;
            } else if (splitDateBy == "year") {
                if (addMonth) {
                    dateFromSplitMonth++;
                    return dateFromSplitYear + "-" + dateFromSplitMonth + "-" + dateFromSplitDay;
                }
                return dateFromSplitYear + "-" + dateFromSplitMonth + "-" + dateFromSplitDay;
            }
        },
        getDateDifference(startDate, endDate) {
            const date1 = new Date(startDate);
            const date2 = new Date(endDate);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays;
        },

    }
};
</script>

<style scoped>

</style>