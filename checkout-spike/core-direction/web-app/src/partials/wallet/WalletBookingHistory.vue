<template>
  <div class="booking-history-content-box">
    <wallet-booking-history-header />
    <div class="wallet-table-body">
      <div v-for="(history,index) in bookingHistory" :key="index" class="wallet-table-row flex">
        <div class="small-screen-accordion-box flex">
          <div class="small-screen-accordion-icon-box">
            <accordion-icon />
          </div>
          <p class="small-screen-accordion-title">
            {{ history.class_date }}
          </p>
        </div>
        <ul class="wallet-table-content-list">
          <li class="wallet-table-content-item flex items-center">
            <span class="table-small-screen-heading">Name</span>
            <span class="table-data">{{ history.activityName }}</span>
          </li>
          <li class="wallet-table-content-item flex items-center">
            <span class="table-small-screen-heading">Provider</span>
            <span class="table-data">{{ history.facility }}</span>
          </li>
          <li class="wallet-table-content-item flex items-center">
            <span class="table-small-screen-heading">Invoice Status</span>
            <span class="table-data">{{ history.is_free ? "Free" : "Paid" }}</span>
          </li>
          <li class="wallet-table-content-item flex items-center">
            <span class="table-small-screen-heading">Date</span>
            <span class="table-data">{{ history.class_date }}</span>
          </li>
          <li class="wallet-table-content-item flex items-center">
            <span class="table-small-screen-heading">Time</span>
            <span class="table-data">{{ history.startTime }} - {{ history.endTime }}</span>
          </li>
          <li class="wallet-table-content-item flex items-center">
            <span class="table-small-screen-heading">Action</span>
            <span class="table-data">
              <button :class="computeButtonClass(history)"
                      :disabled="history.status !== 'Upcoming'"
                      type="button"
                      @click="cancelBooking(history.id)"
              >
                {{ computeButtonLabel(history) }}
              </button>
            </span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import WalletBookingHistoryHeader from "./WalletBookingHistory/WalletBookingHistoryHeader";
import * as toastr from "toastr";
import {mapGetters} from "vuex";
import AccordionIcon from "@/svgs/arrows/accordion-icon";

export default {
    name: "WalletBookingHistory",
    components: {AccordionIcon, WalletBookingHistoryHeader},
    data() {
        return {
            bookingHistory: [],
        };
    },
    computed: {
        ...mapGetters({
            userProfile: "getStoreUserProfileGetters",
        }),
        computeButtonClass() {
            return (history) =>
                `${history.status === "Upcoming" ? "btn-cancel" : "btn-checked-in"} rounded-full`;
        },
        computeButtonLabel() {
            return (history) => (history.status !== "Upcoming" ? history.status : "Cancel");
        },
    },
    created() {
        this.collectBookingInformation();
    },
    methods: {
        async collectBookingInformation() {
            const [bookingHistory, upcomingBooking] = await Promise.all([
                this.getBookingHistory(),
                this.getUpcomingBooking()
            ]);
            this.bookingHistory = [
                ...bookingHistory,
                ...upcomingBooking
            ].sort((firstBooking, secondBooking) =>
                new Date(secondBooking.book_date) - new Date(firstBooking.book_date)
            );
        },
        getBookingHistory() {
            return this.oldApi("get", this.constants.getUrl("bookingHistory"))
                .then(response => {
                    const bookingHistoryData = response.data;
                    bookingHistoryData.forEach(this.updateBookingHistoryStatus);
                    return bookingHistoryData;
                })
                .catch(this.handleError);
        },
        getUpcomingBooking() {
            return this.oldApi("get", `${this.constants.getUrl("upComingBooking")}?username=${this.userProfile().username}`)
                .then(response => {
                    const upComingBookingData = response.data;
                    upComingBookingData.forEach(this.updateUpComingBookingStatus);
                    return upComingBookingData;
                })
                .catch(this.handleError);
        },
        updateBookingHistoryStatus(booking) {
            if (booking.status === "expired" && booking.checkin) {
                booking.status = "Attended";
            }
        },
        updateUpComingBookingStatus(booking) {
            if (booking.status === "booked") {
                booking.status = "Upcoming";
            }
        },
        cancelBooking(id) {
            const payload = {
                "slot_id": id
            };
            this.oldApi("post", this.constants.getUrl("cancelActivity"), payload, true)
                .then(async response => {
                    toastr.success(response.data[0].message);
                    await this.collectBookingInformation();
                })
                .catch(this.handleError);
        },
        handleError(error) {
            toastr.error(error.message);
        }

    }

};
</script>

<style scoped>
span.table-data button {
  width: 100px;
}
</style>