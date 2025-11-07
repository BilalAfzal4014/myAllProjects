<template>
  <swiper ref="mySwiper" :options="swiperOptions">
    <swiper-slide v-for="(slideData,index) in data" :key="`slide-data-${index}`">
      <slot :slideData="slideData" />
    </swiper-slide>
  </swiper>
</template>

<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";

import "swiper/css/swiper.css";

export default {
    name: "SwiperComponent",
    components: {
        Swiper,
        SwiperSlide
    },
    data() {
        return {
            swiperOptions: {
                slidesPerView: "auto",
            },
        };
    },
    computed: {
        swiper() {
            return this.$refs.mySwiper.$swiper;
        },
    },
    mounted() {
        this.swiper.slideTo(0, 1000, false);
    },
    props: {
        data: {
            type: Array,
            required: true
        }

    }

};
</script>
