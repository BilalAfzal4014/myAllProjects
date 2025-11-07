<template>
  <div class="section-body">
    <booking-modal
      v-if="showModal"
      :book-class="bookClass"
      @clicked="closedModal"
      @confirm="confirmModal"
    />
    <ConfirmBooking v-if="isConfirm" @clicked="closeConfirmModal" />
    <SocialSharingOnSignup :show-modal="showSharingModal" @setShowActivityModal="hideSocialSharingModal" />
    <div class="swiper-container mass-events-swiper-container">
      <swiper ref="mySwiper" :options="swiperOptions">
        <swiper-slide
          v-for="data in activity.list"
          :key="data.id"
          class="swiper-slide"
        >
          <div class="booking-card">
            <div class="booking-card-header flex items-start">
              <router-link
                :to="{
                  name: 'Booking',
                  params: { slug: data?.company_slug }
                }"
              >
                <div class="business-logo-box rounded-full">
                  <PreviewImage
                    :src="`member/${data.facilityImage}`"
                    :useSrcset="false"
                    alt="company-profile-image"
                    class="rounded-full"
                    type="thumbnail"
                  />
                </div>
                <h3 class="business-name text-left">
                  {{ data.facility }}
                </h3>
              </router-link>
            </div>
            <div class="booking-card-body">
              <div class="booking-info-box flex items-center">
                <p class="booking-title">
                  {{ data.name }}
                </p>
                <div class="booking-more-info-box">
                  <button class="booking-desc-icon-box">
                    <svg
                      fill="none"
                      height="14"
                      viewBox="0 0 13 14"
                      width="13"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M7.28 13.5117C6.76 13.5117 6.24 13.5117 5.72 13.5117C5.66222 13.5002 5.61022 13.4828 5.55244 13.4771C3.79022 13.1939 2.36311 12.3504 1.28267 10.9348C0.566222 10.0046 0.179111 8.9415 0 7.79172C0 7.25439 0 6.71127 0 6.17394C0.0288889 6.04105 0.0577778 5.91394 0.0866667 5.78105C0.427556 4.05927 1.32311 2.69572 2.74444 1.67305C3.63422 1.03172 4.64533 0.685052 5.72 0.511719C6.24 0.511719 6.76 0.511719 7.28 0.511719C7.33778 0.523274 7.38978 0.540608 7.44756 0.552163C9.81067 0.968163 11.492 2.26239 12.4916 4.44061C12.7631 5.02416 12.8844 5.65394 13 6.2895C13 6.79216 13 7.28905 13 7.79172C12.9827 7.87839 12.9653 7.95927 12.948 8.04594C12.6187 9.87172 11.7 11.3277 10.1804 12.3966C9.308 13.0091 8.32578 13.3384 7.28 13.5117ZM6.5 12.8935C9.78756 12.8473 12.376 10.2299 12.376 7.03483C12.376 3.93216 9.93778 1.10105 6.46533 1.12994C3.07956 1.15883 0.652889 3.81661 0.618222 6.86727C0.577778 10.2588 3.24711 12.9397 6.5 12.8935Z"
                        fill="black"
                      />
                      <path
                        d="M6.38485 5.91857C7.1533 5.91857 7.52308 6.32302 7.43063 7.07991C7.37285 7.55947 7.18219 8.00435 7.06663 8.47236C7.01463 8.69769 6.95108 8.91724 6.91063 9.14836C6.85863 9.45458 6.98574 9.6048 7.29196 9.62791C7.40752 9.63369 7.52307 9.61636 7.63863 9.62213C7.71952 9.62791 7.84663 9.51236 7.8813 9.66258C7.91019 9.77236 7.86974 9.89369 7.77152 9.97458C7.38441 10.2924 6.36752 10.3964 5.91685 10.1595C5.57019 9.98036 5.40841 9.67991 5.44308 9.28124C5.4893 8.73813 5.6973 8.23547 5.83019 7.71547C5.88797 7.48435 5.97463 7.25324 5.98041 7.01058C5.99197 6.68702 5.87641 6.57724 5.55285 6.57724C5.47774 6.57724 5.39686 6.60035 5.32174 6.59458C5.22352 6.5888 5.0733 6.73324 5.03286 6.56569C5.00397 6.42702 5.05597 6.25946 5.24086 6.19591C5.61641 6.06302 5.98041 5.90702 6.38485 5.91857Z"
                        fill="black"
                      />
                      <path
                        d="M7.9974 4.526C7.9974 4.95933 7.59873 5.31756 7.13651 5.306C6.68584 5.29445 6.29295 4.93045 6.28717 4.526C6.28139 4.10422 6.68006 3.73444 7.14806 3.73444C7.61606 3.72867 7.9974 4.08689 7.9974 4.526Z"
                        fill="black"
                      />
                    </svg>
                  </button>
                  <div class="booking-desc-box">
                    <p class="booking-desc">
                      {{ data.description }}
                    </p>
                  </div>
                </div>
              </div>
              <p class="booking-activity-time">
                <span style="font-weight:700">{{
                  data.startDate.split(',')[0]
                }},&nbsp;{{ data.startDate.split(',')[1] }} </span> |
                {{ data.startTime }} - {{ data.endTime }}
              </p>
              <ul class="booking-activity-info-list">
                <li
                  class="booking-activity-info-item flex items-center flex-wrap"
                >
                  <span
                    class="booking-activity-info-item-icon-box flex justify-center"
                  >
                    <svg
                      fill="none"
                      height="12"
                      viewBox="0 0 8 12"
                      width="8"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M2.82828 0.703125H5.29293C5.87175 0.703123 6.35647 1.00189 6.82832 1.6541C7.02198 1.92179 7.18812 2.46398 7.3052 3.01901C7.36056 3.28142 7.40041 3.52319 7.42642 3.69957C7.43939 3.78753 7.44883 3.85866 7.45497 3.90727L7.46172 3.96244L7.46222 3.96678L7.46333 3.98855C7.4645 4.01255 7.46614 4.04851 7.46793 4.09493C7.4715 4.18781 7.47563 4.32231 7.47778 4.48651C7.48209 4.81579 7.47833 5.26032 7.44669 5.72611C7.41484 6.19506 7.35576 6.66898 7.25475 7.06324C7.14778 7.48076 7.01714 7.71244 6.9106 7.80252C6.53198 8.12263 6.18477 8.24463 5.75432 8.29215C5.43354 8.32755 5.09668 8.32117 4.66948 8.31306C4.45829 8.30906 4.22502 8.30464 3.96071 8.30464C3.70191 8.30464 3.46647 8.30891 3.24917 8.31286C2.79766 8.32106 2.4245 8.32783 2.08302 8.29188C1.61228 8.24231 1.31816 8.1182 1.09537 7.85866C0.992795 7.73917 0.883717 7.52395 0.78768 7.20571C0.694198 6.89593 0.624236 6.5267 0.576728 6.13413C0.481412 5.34652 0.482363 4.51582 0.538783 3.97701C0.659912 2.82018 0.732814 2.32132 1.21386 1.65317C1.70352 0.973042 2.25981 0.703126 2.82828 0.703125Z"
                        stroke="black"
                      />
                      <path
                        d="M2.83564 9.71777H5.3067C6.06234 9.71777 6.62956 9.81137 7.10088 9.96107C7.26366 10.0128 7.37489 10.103 7.44638 10.1809C7.46842 10.2049 7.48618 10.2272 7.49988 10.246C7.50071 10.2813 7.49733 10.3203 7.48672 10.3562C7.47586 10.393 7.4582 10.4252 7.4254 10.4546C7.39207 10.4845 7.3174 10.5345 7.15688 10.5657C6.42344 10.7082 5.75201 10.7055 4.64374 10.7011C4.43595 10.7003 4.2128 10.6994 3.97101 10.6994C3.73283 10.6994 3.50759 10.7003 3.29416 10.7012C2.82591 10.7031 2.41449 10.7048 2.04803 10.696C1.51166 10.683 1.1239 10.6476 0.847128 10.5735C0.627075 10.5146 0.550125 10.4166 0.522953 10.365C0.494058 10.3101 0.501212 10.2755 0.500083 10.2752C0.499783 10.2751 0.498898 10.2775 0.496597 10.2825C0.556342 10.1514 0.569723 10.1399 0.585592 10.1261L0.585822 10.1259C0.613956 10.1016 0.697332 10.0454 0.962208 9.96086C1.46501 9.80038 2.10872 9.71777 2.83564 9.71777Z"
                        stroke="black"
                      />
                    </svg>
                  </span>
                  <strong>
                    Booking slots: &nbsp;
                  </strong>
                  {{ data.booked_slots }}/{{ data.slots }}
                  <span class="booking-tag">{{ data.isFree ? "Free" : "Paid" }}</span>
                </li>
                <li
                  class="booking-activity-info-item flex items-center flex-wrap"
                >
                  <span
                    class="booking-activity-info-item-icon-box flex justify-center"
                  >
                    <PreviewImage
                      :src="`activity/${data.activityTypeImage}`"
                      :useSrcset="false"
                      alt="company-profile-image"
                      type="thumbnail"
                    />
                  </span>
                  <strong class="booking-activity-name">{{ data.actt_name }} </strong><span
                    v-if="data.instructor_name"
                  >with {{ data.instructor_name }}</span>
                </li>
                <li
                  class="booking-activity-info-item flex items-center flex-wrap"
                >
                  <span
                    class="booking-activity-info-item-icon-box flex justify-center"
                  >
                    <BookingMapIcon />
                  </span>
                  <span v-if="!data.offer_online" class="address">
                    {{ data.zone }}
                  </span>
                  <span v-if="data.offer_online" class="address">
                    Online
                  </span>
                </li>
              </ul>
            </div>
            <ActivityBookingCardFooter
              :data="data"
              @bookActivity="bookActivity(data)"
            />
          </div>
        </swiper-slide>
      </swiper>
    </div>
  </div>
</template>

<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import ActivityBookingCardFooter from "@/partials/activity/ActivityBookingCardFooter";
import BookingMapIcon from "@/svgs/BookingMapIcon";
import BookingModal from "@/partials/BookingModal";
import ConfirmBooking from "@/partials/ConfirmBooking";
import SocialSharingOnSignup from "@/partials/SocialSharingOnSignup";
import bookActivityMixin from "@/mixin/bookActivityMixin";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "ActivityBookingLists",
  mixins: [bookActivityMixin],
  props: {
    activity: {
      type: Object,
      required: true
    },
  },
  components: {
    PreviewImage,
    SocialSharingOnSignup,
    ConfirmBooking,
    BookingModal,
    BookingMapIcon,
    ActivityBookingCardFooter,
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      showModal: false,
      bookClass: {},
      isConfirm: false,
      swiperOptions: {
        slidesPerView: "auto",
      },
      showSharingModal: false
    };
  },
  computed: {
    swiper() {
      return this.$refs.mySwiper.$swiper;
    },
  },
};
</script>

<style scoped>
.booking-card-header.flex.items-start a {
  width: 100%;
  display: flex;
  align-items: flex-start;
}
</style>
