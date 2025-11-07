<template>
  <div>
    <div v-for="(company,index) in companies" :key="`category-${index}`">
      <section-heading-component v-if="company.company.length > 0" :name="company.category_name" :title="company.title"
                                 :url="company.link"
      />
      <div v-if="company.company.length > 0" class="section-body">
        <div class="custom-container">
          <div class="content-container">
            <swiper ref="mySwiper" :options="swiperOptions" class="swiper-wrapper">
              <swiper-slide v-for="(detail, i) in company.company" :key="`company-${i}`">
                <card :company="detail" />
              </swiper-slide>
            </swiper>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SectionHeadingComponent from "../partials/section-heading";
import card from "../partials/card";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";

export default {
    name: "Cards",
    components: {SectionHeadingComponent, card, Swiper, SwiperSlide},
    props: {
        companies: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            swiperOptions: {
                slidesPerView: "auto",
                spaceBetween: 15,
                loop: false,
            }
        };
    },
    computed: {
        swiper() {
            return this.$refs.mySwiper.$swiper;
        }
    }

};
</script>

<style scoped>
.card .card-body .btn-fav-box .fav:hover {
  background: transparent !important;
}

</style>