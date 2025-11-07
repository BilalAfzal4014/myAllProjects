<template>
  <div class="input-field-date-box lg:mr-4 ml-2">
    <input id="calendar" type="date" style="position:relative;top:-7px;" class="calendar rounded-full" placeholder="Select Date">
  </div>
</template>

<script>
export default {
    name: "SearchCalendar",
    methods:{
        showDatePicker(){
            document.getElementById("calendar").style.visibility="visible";
        }
  
    }
};
</script>

<style scoped>
/*input[type="date"]::-webkit-calendar-picker-indicator {*/
/*  background: transparent;*/
/*  bottom: 0;*/
/*  color: transparent;*/
/*  cursor: pointer;*/
/*  height: auto;*/
/*  left: 0;*/
/*  position: absolute;*/
/*  right: 0;*/
/*  top: 0;*/
/*  width: auto;*/
/*}*/

</style>