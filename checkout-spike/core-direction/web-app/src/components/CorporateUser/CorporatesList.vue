<template>
  <div v-if="corporates.length">
    <section-heading-component :name="name" :title="title" :url="url" />
    <div>
      <div class="section-body">
        <div class="custom-container">
          <div class="content-container">
            <swiper ref="mySwiper" :options="swiperOptions" class="swiper-wrapper">
              <swiper-slide v-for="(company,index) in corporates" :key="`category-${index}`">
                <card :company="company" :show-fav="false" />
              </swiper-slide>
            </swiper>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SectionHeadingComponent from "@/partials/section-heading";
import card from "@/partials/card";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import {getUserCorporates} from "@/apiManager/user";

export default {
    name: "CorporatesList",
    components: {SectionHeadingComponent, card, Swiper, SwiperSlide},
    data() {
        return {
            swiperOptions: {
                slidesPerView: "auto",
                spaceBetween: 15,
                loop: false,
            },
            name: "My Corporates",
            title: "Browse All",
            url: "/corporates",
            corporates: []
        };
    },
    computed: {
        swiper() {
            return this.$refs.mySwiper.$swiper;
        }
    },
    mounted() {
        this.getCorporatesList();
    },
    methods: {
        getCorporatesList() {
            getUserCorporates(20).then(response => {
              this.corporates = response.data.companies;
            }).catch(error => {
              return error;
            });
        }
    }
};
</script>

<style scoped>

</style>
