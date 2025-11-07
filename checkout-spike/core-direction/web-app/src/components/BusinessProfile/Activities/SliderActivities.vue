<template>
  <div>
    <booking-modal
      v-if="showModal"
      :book-class="bookClass"
      @clicked="closedModal"
      @confirm="confirmModal"
    />
    <swiper v-if="sliderData.length > 0" :options="activityOption" class="swiper">
      <swiper-slide v-for="(activity,index) in sliderData" :key="index">
        <ActivityCard :activity="activity" :activityAddress="activity.address ??''"
                      :activityEndTime="activity.endTime ?? ''" :activityFree="activity.isFree"
                      :activityIcon="activity.activityImage ?? ''" :activityIconName="activity.actt_name ?? ''"
                      :activityName="activity.name ?? ''" :activityStartDate="activity.startDate ?? ''"
                      :activityStartTime="activity.startTime ?? ''"
                      :activityTypeImage="activity.activityTypeImage ?? ''"
                      :facilityImage="activity.facilityImage ?? ''"
                      :facilityName="activity.facility ?? ''"
                      :activityBookedSlots="activity.booked_slots ?? null"
                      :activityTotalSlots="activity.slots ?? null"
                      @onBookActivity="bookActivity"
        />
      </swiper-slide>
      <swiper-slide>
        <button class="btn-browse-more" @click="$emit('navigateToTab', 'activities')">
          <span> Browse more </span>
          <img alt="browse more" src="@/assets/images/browse-more-icon.svg">
        </button>
      </swiper-slide>
      <div slot="pagination" class="swiper-pagination" />
    </swiper>
  </div>
</template>
<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import ActivityCard from "./ActivityCard.vue";
import bookActivityMixin from "@/mixin/bookActivityMixin";
import BookingModal from "@/partials/BookingModal";

export default {
  name: "SliderActivities",
  mixins: [bookActivityMixin],
  components: {
    BookingModal,
    Swiper,
    SwiperSlide,
    ActivityCard,
  },
  props: {
    sliderData: {
      type: Array,
      default: () => [],
    }
  },
  data() {
    return {
      activityOption: {
        slidesPerView: 4,
        spaceBetween: 20,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        breakpoints: {
          1200: {
            slidesPerView: 4,
            spaceBetween: 20
          },
          1024: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          320: {
            slidesPerView: 1,
            spaceBetween: 20
          }
        }
      },
      showModal: false,
      bookClass: [],
      isConfirm: false,
      showSharingModal: false
    };
  },

};
</script>

<style lang="scss" scoped>
.swiper-slide {
  width: 364px !important;
  padding: 16px 0 32px;
}

.swiper-container {
  overflow: inherit;
  margin-right: -200px;
  width: calc(100% + 200px);
}

.btn-browse-more {
  display: flex;
  align-items: center;
  justify-content: center;
  column-gap: 8px;
  width: 176px;
  height: 100%;
  min-height: 439px;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  padding: 10px;
  text-transform: capitalize;
  border-radius: 16px;
  box-shadow: 0px 12px 16px 0px rgba(28, 4, 47, 0.08), 0px 4px 6px 0px rgba(28, 4, 47, 0.03);
  background: linear-gradient(270.17deg, #7812C6 15.31%, rgba(93, 13, 153, 0.65) 99.74%);
  border: 0.5px solid #7812c6;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.swiper-pagination {
  display: none;
}
</style>