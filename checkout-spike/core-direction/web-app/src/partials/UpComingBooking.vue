<template>
  <section id="bookings">
    <CheckInBookingModal v-if="checkInBookingModal" :check-in-data="checkInData" @close="closed" />
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            Upcoming booking
          </h4>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="custom-container">
        <div class="content-container relative">
          <swiper
            ref="mySwiper" :options="swiperOptions"
          >
            <!-- Slides -->
            <swiper-slide v-for="booking in upcomingBookings" :key="booking.id">
              <!-- booking-listing -->
              <div class="booking-listing">
                <button class="booking-listing-outer-box" type="button" @click="bookingData($event,booking)">
                  <p class="activity-status">
                    {{ booking.is_free ? "Free" : "Paid" }}
                  </p>
                  <div class="booking-listing-inner-box">
                    <div class="booking-listing-icon-box">
                      <img :src="booking.activityImage" style="max-width:50px;max-height:50px;">
                    </div>
                    <div class="booking-listing-content-box">
                      <p class="exercise-name uppercase">
                        {{ booking.activityName }}
                      </p>
                      <p class="instructor-name">
                        {{ booking.instructor_name }}
                      </p>
                      <p class="destination">
                        {{ booking.address }}
                      </p>
                      <p class="date">
                        {{ booking.class_date }}
                      </p>
                      <p class="time">
                        {{ booking.startTime }} - {{ booking.endTime }}
                      </p>
                    </div>
                  </div>
                </button>
              </div>
            </swiper-slide>
          </swiper>
          <div class="navigation hidden md:block">
            <button class="left_swiper_arrow" @click="prev">
              <LeftArrowIcon />
            </button>
            <button class="right_swiper_arrow" @click="next">
              <RightArrowIcon />
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import moment from "moment";
import "swiper/css/swiper.css";
import CheckInBookingModal from "@/partials/CheckInBookingModal";
import LeftArrowIcon from "@/svgs/LeftArrowIcon";
import RightArrowIcon from "@/svgs/RightArrowIcon";

export default {
    name: "UpComingBooking",
    components: {
        RightArrowIcon,
        LeftArrowIcon,
        CheckInBookingModal, Swiper,
        SwiperSlide
    },
    props: {
        upcomingBookings: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            checkInData: [],
            checkInBookingModal: false,
            swiperOptions: {
                slidesPerView: "auto",
            }
        };
    },
    computed: {
        swiper() {
            return this.$refs.mySwiper.$swiper;
        }
    },
    mounted() {
        this.swiper.slideTo(0, 1000, false);
    },
    methods: {
        bookingData(event, booking) {
            const body = document.querySelector("body");
            body.classList.add("overflow-y-hidden");
            this.checkInBookingModal = true;
            this.checkInData = booking;
            this.checkInData.startDate = moment(this.checkInData.startDate).format("YYYY-MM-DD");

        },
        closed() {
            this.checkInBookingModal = false;
        },
        prev() {
            this.$refs.mySwiper.$swiper.slidePrev();
        },
        next() {
            this.$refs.mySwiper.$swiper.slideNext();
        }
    }
};
</script>

<style scoped>
#bookings {
  margin-bottom: 48px;
}
.section-body .swiper-container .swiper-wrapper .swiper-slide {
  min-height: unset;
}

</style>