<template>
  <div>
    <div class="business-profile-activities-page">
      <div class="business-profile-activities-filter">
        <div class="filter-box flex  mx-auto items-center rounded">
          <activity-type @clicked="getSelectedActivity" />
          <ZoneType :companyId="companyId" @clicked="getSelectedZone" />
          <div class="input-field-date-box lg:mr-4 ml-2">
            <datepicker ref="datepicker" v-model="date" :disabled-dates="disabledDates" format="yyyy-MM-dd"
                        input-class="border_radius_9999" placeholder="Select date"
            />
            <BlackCalendarIcon @showed="showDatePicker" />
          </div>
          <div class="search-btn-box lg:mb-0 mb-4">
            <button class=" rounded-full" @click="searchActivity">
              Search
            </button>
          </div>
          <ResetFilterBooking @reset="resetFilter" />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import ZoneType from "@/partials/ZoneType";
import ActivityType from "@/partials/ActivityType";
import BlackCalendarIcon from "@/svgs/BlackCalendarIcon";
import moment from "moment";
import ResetFilterBooking from "@/partials/ResetFilterBooking";

export default {
  name: "BusinessProfileFilter",
  components: {
    ResetFilterBooking,
    BlackCalendarIcon,
    ActivityType,
    ZoneType,
    Datepicker
  },
  props: {
    companyId: {
      type: Number,
      required: false
    }
  },
  data() {
    return {
      selectedActivities: [],
      selectedZones: [],
      date: "",
      endDate: "",
      disabledDates: {
        to: moment().subtract(1, "days").toDate()
      }
    };
  },
  methods: {
    getSelectedActivity(value) {
      if (this.selectedActivities.indexOf(value) !== -1) {
        this.selectedActivities = this.selectedActivities.filter((item) => item != value);
      } else {
        this.selectedActivities.push(value);
      }
    },
    getSelectedZone(value) {
      if (this.selectedZones.indexOf(value) !== -1) {
        this.selectedZones = this.selectedZones.filter((item) => item != value);
      } else {
        this.selectedZones.push(value);
      }
    },
    showDatePicker() {
      this.$refs.datepicker.showCalendar();
    },
    searchActivity() {
      if (this.date !== "") {
        this.date = moment(this.date).format("YYYY-MM-DD");
        let nextSevenDays = moment(this.date).add(7, "days");
        this.endDate = moment(nextSevenDays).format("YYYY-MM-DD");
      }
      if (this.selectedActivities.length == 0 && this.selectedZones.length == 0 && !this.date) {
        toastr.error("Please either select activity type or zone or date ");
        return;
      }
      this.$emit("searched", {
        "activity_type_ids": this.selectedActivities,
        "zone_ids": this.selectedZones,
        "start_date": this.date,
        "end_date": this.date
      });
    },
    resetFilter() {
      this.selectedActivities = [];
      this.selectedZones = [];
      this.date = "";
      this.$emit("reset", "resetFilter");
    },


  }
};
</script>

<style scoped>
@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter {
    margin-left: 0;
    margin-right: 0;
  }

  .input-field-category-box.mr-2 {
    margin-left: auto;
  }
}

@media screen and (max-width: 767px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter[data-v-8999f146] {
    margin-top: 0 !important;
  }
}

</style>