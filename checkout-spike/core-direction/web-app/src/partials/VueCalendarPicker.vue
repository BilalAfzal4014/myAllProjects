<template>
  <date-picker format="YYYY-MM-DD" :inline="true" type="date" range="true" />
</template>

<script>
import DatePicker from "vue2-datepicker";
// import 'vue2-datepicker/index.css';
import "../assets/datepicker.css";
export default {
    components: { DatePicker },
    name: "VueCalendarPicker",
  
};
</script>

<style scoped>
.mx-datepicker-inline {
  width: 101%;
}
.mx-datepicker-body  .mx-calendar .mx-calendar-panel-date {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  width: 100% !important;
  padding: 6px 12px;
}
</style>