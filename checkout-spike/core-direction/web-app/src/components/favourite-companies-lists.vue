<template>
  <div v-if="companies.length">
    <section-heading-component :name="name" :title="title" :url="url" />
    <div>
      <div class="section-body">
        <div class="custom-container">
          <div class="content-container">
            <swiper ref="mySwiper" :options="swiperOptions" class="swiper-wrapper">
              <swiper-slide v-for="(company,index) in companies" :key="`category-${index}`">
                <card :company="company" :is-always-favourite="isAlwaysFavourite"
                      @remove-favourite-company="handleRemoveFavouriteCompany"
                />
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

export default {
    name: "FavouriteCompaniesLists",
    components: {SectionHeadingComponent, card, Swiper, SwiperSlide},
    props: {
        companies: {
            type: Array,
            required: true,
            default: (() => [])
        }
    },
    data() {
        return {
            swiperOptions: {
                slidesPerView: "auto",
                spaceBetween: 15,
                loop: false,
            },
            name: "My Favourites",
            title: "Browse All",
            isAlwaysFavourite: true,
            url: "/favourite-companies"
        };
    },
    computed: {
        swiper() {
            return this.$refs.mySwiper.$swiper;
        }
    },
    methods: {
        handleRemoveFavouriteCompany(eventPayload) {
            this.$emit("remove-favourite-company", eventPayload);
        },
    }
};
</script>

<style scoped>

</style>