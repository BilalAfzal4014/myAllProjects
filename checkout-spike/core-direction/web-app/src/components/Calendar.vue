<template>
  <main id="main">
    <diary-calendar :username="$store.getters.getStoreUserProfileGetters().username"
                    @onRefetchAllActivities="refetchBookings()"
    />
    <UpComingBooking v-if="upcomingBookings.length > 0" :upcoming-bookings="upcomingBookings" />
    <CalendarBookingHistory v-if="bookingHistory.length > 0" :booking-history="bookingHistory" />
  </main>
</template>

<script>
import * as toastr from "toastr";
import UpComingBooking from "../partials/UpComingBooking";
import CalendarBookingHistory from "../partials/CalendarBookingHistory";
import moment from "moment";
import {updateMetaInformation} from "../utils";
import DiaryCalendar from "@/partials/activity-diary-calendar/diary-calendar";

export default {
  name: "Calendar",
  components: {
    DiaryCalendar,
    CalendarBookingHistory, UpComingBooking
  },
  data() {
    return {
      upcomingBookings: [],
      bookingHistory: [],
      user: this.$store.getters.getStoreUserProfileGetters(),
      interests: []
    };
  },
  methods: {
    getBookingHistory() {
      this.oldApi("get",
        this.constants.getUrl("bookingHistory"),
      ).then((response) => {
        if (response.data.length > 0) {
          this.bookingHistory = response.data;
          this.bookingHistory.forEach((booking) => {
            booking.activityImage = this.constants.getImageUrl("activity/" + booking.activityImage);
            booking.facilityImage = this.constants.getImageUrl("member/" + booking.facilityImage);
            if (booking.status == "expired") {
              if (booking.checkin) {
                booking.status = "Attended";
              }
            } else {
              booking.status = booking.status;
            }
          });
        }
      }).catch((error) => {
        toastr.error(error[0].response.data.message);
      });
    },
    getUpcomingBooking() {
      this.upcomingBookings = [];
      this.oldApi("get",
        `${this.constants.getUrl("upComingBooking")}?username=${this.user.username}`,
      ).then((response) => {
        if (response.data.length > 0) {
          this.upcomingBookings = response.data;
          this.upcomingBookings.forEach((booking) => {
            booking.activityImage = this.constants.getImageUrl("activity/" + booking.activityImage);
            booking.endDate = moment(booking.endDate).format("YYYY-MM-DD");
          });
        }
      }).catch((error) => {
        toastr.error(error[0].response.data.message);
      });
    },
    refetchBookings() {
      this.getBookingHistory();
      this.getUpcomingBooking();
    }

  },
  created() {
    this.getBookingHistory();
    this.getUpcomingBooking();
  },
  mounted() {
    updateMetaInformation("My Calender | Core Direction", "Core Direction, Coredirection, Core Direction calendar", "Keep track and manage all of your activity bookings in one place.", "My Calender | Core Direction", "Keep track and manage all of your activity bookings in one place.", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/calendar");
  }
};
</script>

<style scoped>
.fc-button-group {
  background: yellow;
}
</style>