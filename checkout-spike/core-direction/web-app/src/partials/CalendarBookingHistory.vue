<template>
  <div class="booking-history">
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            Booking History
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
            <swiper-slide v-for="book in bookingHistory" :key="book.id"
                          aria-label="1 / 4" role="group"
                          style="transform: translate3d(0px, 0px, 0px); transition-duration: 0ms;"
            >
              <div class="booking-history-card">
                <div class="booking-card-header flex items-start">
                  <div class="business-logo-box">
                    <img :src="book.facilityImage" alt="" class="rounded-full">
                  </div>
                  <h3 class="business-name text-left">
                    {{ book.facility }}
                  </h3>
                </div>
                <div class="booking-card-body">
                  <div class="booking-info-box flex items-center">
                    <p class="booking-title">
                      {{ book.activityName }}
                    </p>
                  </div>
                  <p class="booking-activity-time">
                    {{ book.startTime }} - {{ book.endTime }}
                  </p>
                  <p class="booking-activity-time">
                    {{ book.class_date }}
                  </p>
                  <ul class="booking-activity-info-list">
                    <li class="booking-activity-info-item flex items-center flex-wrap">
                      <span class="booking-activity-info-item-icon-box flex justify-center">
                        <img :src="book.activityImage">
                      </span>
                      <span class="booking-activity-name">{{ book.activityName }}
                      </span><span v-if="book.instructor_name">with {{ book.instructor_name }}</span>
                    </li>
                    <li class="booking-activity-info-item flex items-center flex-wrap">
                      <span class="booking-activity-info-item-icon-box flex justify-center">
                        <svg fill="none" height="12" viewBox="0 0 10 12" width="10" xmlns="http://www.w3.org/2000/svg">
                          <path
                            d="M5.00002 0.641602C2.75939 0.641602 0.937012 2.46398 0.937012 4.70461C0.937012 5.44252 1.26862 6.23421 1.28058 6.27006C1.38813 6.524 1.59725 6.91835 1.74961 7.14839L4.53696 11.3667C4.65048 11.54 4.82077 11.6386 5.00002 11.6386C5.17927 11.6386 5.34956 11.54 5.46308 11.3667L8.25043 7.1454C8.40279 6.91536 8.61192 6.52101 8.71947 6.26708C8.7344 6.23421 9.06303 5.43954 9.06303 4.70162C9.06303 2.46398 7.24065 0.641602 5.00002 0.641602ZM5.00002 6.85562C3.81398 6.85562 2.84902 5.89065 2.84902 4.70461C2.84902 3.51857 3.81398 2.55361 5.00002 2.55361C6.18606 2.55361 7.15103 3.51857 7.15103 4.70461C7.15103 5.89065 6.18606 6.85562 5.00002 6.85562Z"
                            fill="black"
                          />
                        </svg>
                      </span>
                      {{ book.address }}
                    </li>
                  </ul>
                </div>
                <div class="booking-card-footer">
                  <button class="btn-book-now uppercase cursor-default" name="button" type="button">
                    {{ book.status }}
                  </button>
                </div>
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
  </div>
</template>

<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import LeftArrowIcon from "../svgs/LeftArrowIcon";
import RightArrowIcon from "../svgs/RightArrowIcon";

export default {
  name: "CalendarBookingHistory",
  props: {
    bookingHistory: {
      type: Array,
      required: true
    },
  },
  components: {
    RightArrowIcon,
    LeftArrowIcon, Swiper,
    SwiperSlide
  },
  data() {
    return {
      swiperOptions: {
        slidesPerView: "auto",
      }
    };
  },
  methods: {
    prev() {
      this.$refs.mySwiper.$swiper.slidePrev();
    },
    next() {
      this.$refs.mySwiper.$swiper.slideNext();
    }
  },
  computed: {
    swiper() {
      return this.$refs.mySwiper.$swiper;
    }
  },
  mounted() {
    this.swiper.slideTo(0, 1000, false);
  }
};
</script>

<style scoped>
.booking-history-card .booking-card-footer .btn-book-now {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  line-height: 14.63px;
  font-weight: 500;
  width: 100%;
  height: 25px;
  background-color: #690fad;
  color: #FFFFFF;
  border-radius: 0px 0px 11px 11px;
}
#booking-history .left_swiper_arrow{
  z-index: 1;
}
</style>