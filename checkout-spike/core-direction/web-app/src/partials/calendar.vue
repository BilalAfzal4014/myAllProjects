<template>
  <div class="calendar">
    <table>
      <thead>
        <tr>
          <day-name dayname="Mon" />
          <day-name dayname="Tue" />
          <day-name dayname="Wed" />
          <day-name dayname="Thu" />
          <day-name dayname="Fri" />
          <day-name dayname="Sat" />
          <day-name dayname="Sun" />
        </tr>
      </thead>
      <tbody>
        <tr>
          <date day="1" />
          <date day="2" />
          <date day="3" />
          <date day="4" />
          <date day="5" />
          <date day="6" />
          <date day="7" />
        </tr>
        <tr>
          <date day="1" />
          <date day="2" />
          <date day="3" />
          <date day="4" />
          <date day="5" />
          <date day="6" />
          <date day="7" />
        </tr>
        <tr>
          <date day="1" />
          <date day="2" />
          <date day="3" />
          <date day="4" />
          <date day="5" />
          <date day="6" />
          <date day="7" />
        </tr>
        <tr>
          <date day="1" />
          <date day="2" />
          <date day="3" />
          <date day="4" />
          <date day="5" />
          <date day="6" />
          <date day="7" />
        </tr>
        <tr>
          <date day="1" />
          <date day="2" />
          <date day="3" />
          <td />
          <td />
          <td />
          <td />
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import DayName from "./calendar/day-name";
import Date from "./calendar/date";
export default {
    name: "Calendar",
    components: {Date, DayName}
};
</script>

<style scoped>

</style>