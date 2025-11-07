<template>
  <div class="container col-sm-4 col-md-7 col-lg-4 mt-5">
    <div class="card">
      <h3 id="monthAndYear" class="card-header" />
      <table id="calendar" class="table table-bordered table-responsive-sm">
        <thead>
          <tr>
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
          </tr>
        </thead>

        <tbody id="calendar-body" />
      </table>

      <div class="form-inline">
        <button id="previous" class="btn btn-outline-primary col-sm-6" @click="previous()">
          Previous
        </button>

        <button id="next" class="btn btn-outline-primary col-sm-6" @click="next()">
          Next
        </button>
      </div>
      <br>
      <form class="form-inline">
        <label class="lead mr-2 ml-2" for="month">Jump To: </label>
        <select id="month" class="form-control col-sm-4" name="month" @change="jump()">
          <option value="0">
            Jan
          </option>
          <option value="1">
            Feb
          </option>
          <option value="2">
            Mar
          </option>
          <option value="3">
            Apr
          </option>
          <option value="4">
            May
          </option>
          <option value="5">
            Jun
          </option>
          <option value="6">
            Jul
          </option>
          <option value="7">
            Aug
          </option>
          <option value="8">
            Sep
          </option>
          <option value="9">
            Oct
          </option>
          <option value="10">
            Nov
          </option>
          <option value="11">
            Dec
          </option>
        </select>


        <label for="year" /><select id="year" class="form-control col-sm-4" name="year" onchange="jump()">
          <option value="1990">
            1990
          </option>
          <option value="1991">
            1991
          </option>
          <option value="1992">
            1992
          </option>
          <option value="1993">
            1993
          </option>
          <option value="1994">
            1994
          </option>
          <option value="1995">
            1995
          </option>
          <option value="1996">
            1996
          </option>
          <option value="1997">
            1997
          </option>
          <option value="1998">
            1998
          </option>
          <option value="1999">
            1999
          </option>
          <option value="2000">
            2000
          </option>
          <option value="2001">
            2001
          </option>
          <option value="2002">
            2002
          </option>
          <option value="2003">
            2003
          </option>
          <option value="2004">
            2004
          </option>
          <option value="2005">
            2005
          </option>
          <option value="2006">
            2006
          </option>
          <option value="2007">
            2007
          </option>
          <option value="2008">
            2008
          </option>
          <option value="2009">
            2009
          </option>
          <option value="2010">
            2010
          </option>
          <option value="2011">
            2011
          </option>
          <option value="2012">
            2012
          </option>
          <option value="2013">
            2013
          </option>
          <option value="2014">
            2014
          </option>
          <option value="2015">
            2015
          </option>
          <option value="2016">
            2016
          </option>
          <option value="2017">
            2017
          </option>
          <option value="2018">
            2018
          </option>
          <option value="2019">
            2019
          </option>
          <option value="2020">
            2020
          </option>
          <option value="2021">
            2021
          </option>
          <option value="2022">
            2022
          </option>
          <option value="2023">
            2023
          </option>
          <option value="2024">
            2024
          </option>
          <option value="2025">
            2025
          </option>
          <option value="2026">
            2026
          </option>
          <option value="2027">
            2027
          </option>
          <option value="2028">
            2028
          </option>
          <option value="2029">
            2029
          </option>
          <option value="2030">
            2030
          </option>
        </select>
      </form>
    </div>
  </div>
</template>

<script>
export default {
    name: "ChallengerDetailCalendar",
    data () {
        return {
            today : new Date(),
            currentMonth : new Date().getMonth(),
            currentYear : new Date().getFullYear(),
            selectYear : document.getElementById("year"),
            selectMonth : document.getElementById("month"),
            months : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            monthAndYear : document.getElementById("monthAndYear")
        };
    },
    mounted() {
        this.showCalendar(this.currentMonth, this.currentYear);
    },
    methods : {
        next() {
            this.currentYear = (this.currentMonth === 11) ? this.currentYear + 1 : this.currentYear;
            this.currentMonth = (this.currentMonth + 1) % 12;
            this.showCalendar(this.currentMonth, this.currentYear);
        },
        previous() {
            this.currentYear = (this.currentMonth === 0) ? this.currentYear - 1 : this.currentYear;
            this.currentMonth = (this.currentMonth === 0) ? 11 : this.currentMonth - 1;
            this.showCalendar(this.currentMonth, this.currentYear);
        },
        jump() {
            this.currentYear = parseInt(this.selectYear.value);
            this.currentMonth = parseInt(this.selectMonth.value);
            this.showCalendar(this.currentMonth, this.currentYear);
        },
        showCalendar(month, year) {

            let firstDay = (new Date(year, month)).getDay();

            let tbl = document.getElementById("calendar-body"); // body of the calendar

            // clearing all previous cells
            tbl.innerHTML = "";

            let monthAndYear  = document.getElementById("monthAndYear");

            // filing data about month and in the page via DOM.
            monthAndYear.innerHTML = this.months[month] + " " + year;
            this.selectYear.value = year;
            this.selectMonth.value = month;

            // creating all cells
            let date = 1;
            for (let i = 0; i < 6; i++) {
                // creates a table row
                let row = document.createElement("tr");

                //creating individual cells, filing them up with data.
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        let cell = document.createElement("td");
                        let cellText = document.createTextNode("");
                        cell.appendChild(cellText);
                        row.appendChild(cell);
                    }
                    else if (date > this.daysInMonth(month, year)) {
                        break;
                    }

                    else {
                        let cell = document.createElement("td");
                        let cellText = document.createTextNode(date);
                        if (date === this.today.getDate() && year === this.today.getFullYear() && month === this.today.getMonth()) {
                            cell.classList.add("bg-info");
                        } // color today's date
                        cell.appendChild(cellText);
                        row.appendChild(cell);
                        date++;
                    }


                }

                tbl.appendChild(row); // appending each row into calendar body.
            }

        },
        daysInMonth(iMonth, iYear) {
            return 32 - new Date(iYear, iMonth, 32).getDate();
        }
    }
};
</script>

<style scoped>

</style>