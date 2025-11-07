<template>
  <div
    class="booking-card"
  >
    <div class="booking-card-header flex items-start">
      <div class="business-logo-box">
        <img src="" alt="facility-image" class="rounded-full">
      </div>
      <h3 class="business-name text-left">
        {{ facilityName }}
      </h3>
    </div>
    <div class="booking-card-body">
      <div class="booking-info-box flex items-center">
        <p class="booking-title">
          {{ activityName }}
        </p>
        <div v-if="false" class="booking-more-info-box">
          <button class="booking-desc-icon-box" type="button">
            <info-icon />
          </button>
          <div class="booking-desc-box">
            <p class="booking-desc">
              Hello World!
            </p>
          </div>
        </div>
      </div>
      <p class="booking-activity-time">
        <span style="font-weight:700">
          {{ activityStartDate }}
        </span> |
        {{ activityStartTime }} -
        {{ activityEndTime }}
      </p>
      <ul class="booking-activity-info-list">
        <li class="booking-activity-info-item flex items-center flex-wrap">
          <span
            class="booking-activity-info-item-icon-box flex justify-center"
          >
            <booking-slot-icon />
          </span>
          Booking slots: {{ activityBookedSlots }}/{{ activityTotalSlots }}
          <span class="booking-tag">{{ true ? "Free" : "Paid" }}</span>
        </li>
        <li class="booking-activity-info-item flex items-center flex-wrap">
          <span
            class="booking-activity-info-item-icon-box flex justify-center"
          >
            <img src="" alt="activity-image">
          </span>
          <span class="booking-activity-name"> Hello </span><span v-if="true">with Name</span>
        </li>
        <li class="booking-activity-info-item flex items-center flex-wrap">
          <span
            class="booking-activity-info-item-icon-box flex justify-center"
          >
            <map-pointer-icon />
          </span>
          <span class="address">
            {{ true ? "Online" : "" }}
          </span>
        </li>
      </ul>
    </div>
    <div class="booking-card-footer">
      <div class="booking-card-footer">
        <button class="btn-book-now uppercase rounded-full"
                @click="handleClick"
        >
          Book Now
        </button>
      </div>
    </div>
  </div>
</template>

<script>
// import BookingModal from "../partials/BookingModal";
// import ConfirmBooking from "../partials/ConfirmBooking";
// import SocialSharingOnSignup from "./SocialSharingOnSignup";
import InfoIcon from "@/svgs/booking-card/info-icon";
import BookingSlotIcon from "@/svgs/booking-card/booking-slot-icon";
import MapPointerIcon from "@/svgs/booking-card/map-pointer-icon";
import bookActivityMixin from "@/mixin/bookActivityMixin";
// import ActivityBookingCardFooter from "@/partials/activity/ActivityBookingCardFooter";




import {BASE_URLS} from "@/common/constants/constants";
// import ConfirmBooking from "@/partials/ConfirmBooking";
// import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";

export default {
  name: "ActivityCard",
  components: {
    InfoIcon,
    MapPointerIcon,
    BookingSlotIcon,
    // ActivityBookingCardFooter


    // SocialSharingOnSignup, ConfirmBooking
  },
  mixins: [bookActivityMixin],
  data() {
    return {
      showModal: false,
      bookClass: [],
      isConfirm: false,
      showSharingModal: false
    };
  },
  props: {
    activityName: {
      type: String,
      default: "",
    },
    activity: {
      type: Object,
      required: true
    },
    activityStartDate: {
      type: String,
      default: "",
    },
    activityStartTime: {
      type: String,
      default: "",
    },
    activityEndTime: {
      type: String,
      default: "",
    },
    activityIcon: {
      type: String,
      default: "",
    },
    activityIconName: {
      type: String,
      default: "",
    },
    activityTypeImage: {
      type: String,
      default: "",
    },
    activityAddress: {
      type: String,
      default: "",
    },
    activityFree: {
      type: Boolean,
      default: false,
    },
    facilityImage: {
      type: String,
      default: "",
    },
    facilityName: {
      type: String,
      default: "",
    },
    activityBookedSlots:{
      type: Number,
      default: 0,
    },
    activityTotalSlots:{
      type: Number,
      default: 0,
    }
  },
  computed: {
    activitiesBaseUrl() {
      return BASE_URLS.ASSETS_PREFIX_MEMBER;
    },
  },
  methods: {
    bookActivity(data) {
      this.$emit("onBookActivity", data);
    }

  }

};
</script>

<style lang="scss" scoped>

</style>