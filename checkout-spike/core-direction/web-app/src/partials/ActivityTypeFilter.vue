<template>
  <div class="activity-type-filter-box">
    <div class="">
      <div class="flex items-center justify-between">
        <div class="title-box">
          <h4 class="activity-listing-title capitalize">
            Activity Type
          </h4>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="">
        <div class="content-container">
          <div
            class="swiper-container mass-events-swiper-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-free-mode"
          >
            <div id="activity-type-filter-outer-box" aria-live="polite" class="swiper-wrapper">
              <swiper ref="mySwiper" :options="swiperOptions" class="md:hidden sm:block">
                <swiper-slide v-for="activity in activityFilterTypes" :key="activity.name"
                              aria-label="1 / 15" class="swiper-slide swiper-slide-active" role="group"
                >
                  <button type="button" @click="toggleClass(activity.id)">
                    <div class="btn-filter-activity-type flex flex-col justify-between">
                      <div class="btn-filter-activity-type-icon-box">
                        <img :src="activity.imageName">
                      </div>
                      <div class="btn-filter-activity-type-content-box">
                        {{ activity.name }}
                      </div>
                    </div>
                  </button>
                </swiper-slide>
              </swiper>
              <div v-for="activity in activityFilterTypes" :key="activity.name"
                   aria-label="1 / 15" class="swiper-slide swiper-slide-active hidden md:block" role="group"
              >
                <button type="button" @click="toggleClass(activity.id)">
                  <div class="btn-filter-activity-type flex flex-col justify-between">
                    <div class="btn-filter-activity-type-icon-box">
                      <img :src="activity.imageName">
                    </div>
                    <div class="btn-filter-activity-type-content-box">
                      {{ activity.name }}
                    </div>
                  </div>
                </button>
              </div>
            </div>
            <span aria-atomic="true" aria-live="assertive" class="swiper-notification" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as toastr from "toastr";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import {getActivityType} from "@/apiManager/activities";

export default {
    name: "ActivityTypeFilter",
    components: {Swiper, SwiperSlide},
    data() {
        return {
            activityFilterTypes: [],
            selectedActivities: [],
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
        getActivityType().then((response) => {
            this.activityFilterTypes = response.map(activity => {
                activity.imageName = this.constants.getImageUrl(`activity/${activity.imageName}`);
                return activity;
            });
        }).catch((error) => {
            toastr.error(error[0].response.data.errors[0].error);
        });
        this.swiper.slideTo(0, 1000, false);
    },
    methods: {
        toggleClass(id) {
            this.$emit("clicked", id);
        }
    }
};
</script>

<style scoped>

</style>