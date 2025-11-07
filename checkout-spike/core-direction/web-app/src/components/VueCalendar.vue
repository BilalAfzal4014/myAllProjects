<template>
  <div class="md:w-3/4 w-full mx-auto">
    <FullCalendar :options="calendarOptions" />
  </div>
</template>

<script>
import FullCalendar from "@fullcalendar/vue";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";

export default {
    components: {
        FullCalendar // make the <FullCalendar> tag available
    },
    props: {
        events: {
            type: Array,
            required: true
        },
    },

    data() {
        return {
            calendarOptions: {
                plugins: [
                    dayGridPlugin,
                    timeGridPlugin,
                    // bootstrapPlugin,
                    interactionPlugin // needed for dateClick
                ],
                headerToolbar: {
                    left: "prev,next",
                    center: "title",
                    right: "dayGridMonth,dayGridWeek"
                },
                initialView: "dayGridMonth",
                height: "auto",
                events: this.events,
            },


        };
    },
    created() {
    }
};
</script>

<style>
.fc .fc-button-primary {
  color: black !important;
  background-color: transparent !important;
  border-color: transparent;
}

.fc-direction-ltr .fc-button-group > .fc-button:not(:first-child) {
  text-transform: capitalize !important;
}

button.fc-dayGridMonth-button.fc-button.fc-button-primary.fc-button-active {
  border-top-left-radius: 20px;
  background: #690fad !important;
  color: white !important;
  border: none !important;
  width: 90px !important;
  border-bottom-left-radius: 20px;
  text-transform: capitalize;
}

.fc-toolbar-chunk:last-child .fc-button-group {
  background: white !important;
  border-radius: 18px !important;
}

.fc .fc-button-primary:not(:disabled):active:focus, .fc .fc-button-primary:not(:disabled).fc-button-active:focus {
  box-shadow: none !important;
}

.fc .fc-button:focus {
  outline: none !important;
}

button.fc-dayGridMonth-button.fc-button.fc-button-primary:hover {
  border: none !important;
}

th.fc-col-header-cell.fc-day {
  background: #E5E5E4 !important;
}

.fc th, .fc .fc-daygrid-day-number {
  text-align: center !important;
  font-weight: normal !important;
  font-size: 12px !important;
  font-family: 'Montserrat', sans-serif !important;
}

.fc-theme-standard th {
  border-left: 1px solid #ddd !important;
  border-right: 1px solid #ddd !important;
  border-bottom: 1px solid #ddd !important;
  border-top: none !important;
}

.fc .fc-daygrid-day.fc-day-today {
  background-color: transparent !important;
}

button.fc-dayGridWeek-button.fc-button.fc-button-primary.fc-button-active {
  border-top-right-radius: 20px;
  background: #690fad !important;
  color: white !important;
  border: none !important;
  width: 90px !important;
  border-bottom-right-radius: 20px;
  text-transform: capitalize;
}

button.fc-dayGridMonth-button.fc-button.fc-button-primary, button.fc-dayGridWeek-button.fc-button.fc-button-primary {
  width: 90px !important;
}

@media only screen and (max-width: 768px) {
  button.fc-dayGridMonth-button.fc-button.fc-button-primary.fc-button-active {
    border-top-left-radius: 20px;
    background: #690fad !important;
    color: white !important;
    border: none !important;
    width: 60px !important;
    border-bottom-left-radius: 20px;

  }

  button.fc-dayGridMonth-button.fc-button.fc-button-primary, button.fc-dayGridWeek-button.fc-button.fc-button-primary {
    width: 60px !important;
  }

  #advance-filter .advance-filter-btn::before {
    content: "";
    position: absolute;
    width: 30%;
    height: 1px;
    background: #690fad;
    margin-right: 10px;
    left: 0;
  }

  #advance-filter .advance-filter-btn::after {
    content: "";
    position: absolute;
    width: 30%;
    height: 1px;
    background: #690fad;
    margin-left: 10px;
    right: 0;
  }

  .fc-direction-ltr .fc-button-group > .fc-button:not(:first-child) {
    margin-left: -1px;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    text-transform: capitalize !important;
  }
}
</style>