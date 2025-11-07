<template>
  <div>
    <section v-for="booking in bookings" id="bookings" :key="booking.month">
      <div class="custom-container">
        <div class="section-header flex items-center justify-between">
          <div class="title-box">
            <h4 class="section-title capitalize">
              {{ booking.month }}
            </h4>
          </div>
        </div>
      </div>
      <div class="section-body">
        <div class="custom-container">
          <div class="content-container">
            <swiper
              ref="mySwiper" :options="swiperOptions"
            >
              <div v-for="(item,index) in booking.list" :key="index" slot="pagination"
                   class="swiper-pagination"
              />
            </swiper>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import {Swiper} from "vue-awesome-swiper";

import "swiper/css/swiper.css";
import * as toastr from "toastr";

export default {
    name: "UpComingBooking",
    components: {Swiper},

    data() {
        return {
            swiperOptions: {
                slidesPerView: "auto",
                pagination: {
                    el: ".swiper-pagination",
                    type: "bullets",
                    clickable: true,
                },
            },
            bookings: []
        };
    },
    created() {
        this.oldApi("get",
            this.constants.getUrl("upComingBooking") + "?filter_by_month=1",
        ).then((response) => {
            this.bookings = response.data;
        }).catch((error) => {
            toastr.error(error[0].response.data.errors[0].error);
        });
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

</style>