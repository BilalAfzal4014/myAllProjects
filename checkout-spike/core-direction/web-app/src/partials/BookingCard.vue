<template>
  <div class="business-profile-activities-page">
    <ConfirmBooking v-if="isConfirm" @clicked="closeConfirmModal" />
    <SocialSharingOnSignup :show-modal="showSharingModal" @setShowActivityModal="hideSocialSharingModal" />
    <section>
      <booking-modal
        v-if="showModal"
        :book-class="bookClass"
        @clicked="closedModal"
        @confirm="confirmModal"
      />
      <div class="booking-card-box">
        <div
          v-for="list in lists"
          :key="list.id"
          class="booking-card"
        >
          <div class="booking-card-header flex items-start">
            <div class="business-logo-box">
              <img :src="list.facilityImage" alt="facility-image" class="rounded-full">
            </div>
            <h3 class="business-name text-left">
              {{ list.facility }}
            </h3>
          </div>
          <div class="booking-card-body">
            <div class="booking-info-box flex items-center">
              <p class="booking-title">
                {{ list.name }}
              </p>
              <div v-if="list.description" class="booking-more-info-box">
                <button class="booking-desc-icon-box" type="button">
                  <info-icon />
                </button>
                <div class="booking-desc-box">
                  <p class="booking-desc">
                    {{ list.description }}.
                  </p>
                </div>
              </div>
            </div>
            <p class="booking-activity-time">
              <span style="font-weight:700">{{
                list.startDate.split(',')[0]
              }},&nbsp;{{ list.startDate.split(',')[1] }} </span> |
              {{ list.startTime }} -
              {{ list.endTime }}
            </p>
            <ul class="booking-activity-info-list">
              <li class="booking-activity-info-item flex items-center flex-wrap">
                <span
                  class="booking-activity-info-item-icon-box flex justify-center"
                >
                  <booking-slot-icon />
                </span>
                Booking slots: {{ list.booked_slots }}/{{ list.slots }}
                <span class="booking-tag">{{ list.isFree ? "Free" : "Paid" }}</span>
              </li>
              <li class="booking-activity-info-item flex items-center flex-wrap">
                <span
                  class="booking-activity-info-item-icon-box flex justify-center"
                >
                  <img :src="list.activityTypeImage" alt="activity-image">
                </span>
                <span class="booking-activity-name">{{ list.actt_name }} </span><span v-if="list.instructor_name">with {{
                  list.instructor_name
                }}</span>
              </li>
              <li class="booking-activity-info-item flex items-center flex-wrap">
                <span
                  class="booking-activity-info-item-icon-box flex justify-center"
                >
                  <map-pointer-icon />
                </span>
                <span class="address">
                  {{ list.offer_online ? "Online" : list.zone }}
                </span>
              </li>
            </ul>
          </div>
          <div class="booking-card-footer">
            <ActivityBookingCardFooter :data="list" @bookActivity="bookActivity" />
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import BookingModal from "../partials/BookingModal";
import ConfirmBooking from "../partials/ConfirmBooking";
import SocialSharingOnSignup from "./SocialSharingOnSignup";
import InfoIcon from "@/svgs/booking-card/info-icon";
import BookingSlotIcon from "@/svgs/booking-card/booking-slot-icon";
import MapPointerIcon from "@/svgs/booking-card/map-pointer-icon";
import bookActivityMixin from "@/mixin/bookActivityMixin";
import ActivityBookingCardFooter from "@/partials/activity/ActivityBookingCardFooter";

export default {
  name: "BookingCard",
  mixins: [bookActivityMixin],
  components: {
    ActivityBookingCardFooter,
    MapPointerIcon, BookingSlotIcon, InfoIcon, SocialSharingOnSignup, ConfirmBooking, BookingModal
  },
  props: {
    lists: {
      type: Array,
      required: true,
    },
    company_slug: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      showModal: false,
      bookClass: [],
      isConfirm: false,
      showSharingModal: false
    };
  },

};
</script>

<style scoped>
.btn-disabled {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  width: 100%;
  max-width: 262.47px;
  min-height: 44.82px;
  text-align: center;
  background-color: #CAA8F5;
  color: #FFFFFF;
  cursor: not-allowed;
}
</style>
