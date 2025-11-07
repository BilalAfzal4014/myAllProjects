<template>
  <section id="community-challenges">
    <div class="custom-container">
      <div class="section-header flex items-center justify-between">
        <div class="title-box">
          <h4 class="section-title capitalize">
            Challenges & Leaderboards
          </h4>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="custom-container">
        <div class="content-container">
          <div class="swiper-container mass-events-swiper-container">
            <div class="swiper-wrapper">
              <swiper ref="mySwiper" :options="swiperOptions" class="swiper-wrapper">
                <swiper-slide v-for="(challenge, index) in challenges" :key="index" class="swiper-slide">
                  <generic-card :slug="challenge.slug_url" @navigateToDetail="moveToDetail(challenge.slug_url)">
                    <template #card-banner>
                      <PreviewImage
                        :alt="`${challenge.title} banner image`"
                        :src="challenge.cover_photo"
                        :useSrcset="false"
                        classes="card-img rounded-t-lg overflow-hidden"
                        type="cover"
                      />
                      <div class="gym-logo rounded-full absolute top-20 left-6">
                        <PreviewImage
                          :alt="`${challenge.title} logo image`"
                          :src="challenge.logo"
                          :useSrcset="false"
                          classes="rounded-full h-16 w-16"
                          type="logo"
                        />
                      </div>
                    </template>
                    <template #card-info>
                      <div class="card-info-box  flex justify-between px-4 pt-8 pb-4 rounded-b-lg">
                        <div class="gym-info w-full">
                          <a class="card-title" href="#"> {{ challenge.title }} </a>
                          <div class="grid grid-cols-8 gap-1 items-end">
                            <ul class="float-left challenge-info-list flex flex-col col-span-6">
                              <li class="challenge-info-item flex items-center">
                                <span class="challenge-info-label">Rank: </span>
                                <span class="challenge-info-value"> {{ challenge.rank }} /</span>
                                <span class="challenge-info-unselected"> {{ challenge.numberOfParticipent }} </span>
                              </li>
                              <li class="challenge-info-item flex items-center">
                                <span class="challenge-info-label">Start Date:</span>
                                <span class="challenge-info-value"> {{ dateFormat(challenge.start_date) }} </span>
                              </li>
                              <li class="challenge-info-item flex items-center">
                                <span class="challenge-info-label">Duration:</span>
                                <span class="challenge-info-value">{{
                                  getDatesDifference(challenge.start_date, challenge.end_date)
                                }} Days</span>
                              </li>
                            </ul>
                            <div class="float-right col-span-2">
                              <button
                                class="btn-view-challengers ml-auto rounded-full flex items-center justify-center"
                              >
                                <trophy-icon />
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </template>
                  </generic-card>
                </swiper-slide>
              </swiper>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import {getChallengesList} from "@/apiManager/gamification";
import * as toastr from "toastr";
import moment from "moment";
import DefaultImage from "@/assets/images/default_profile_img.png";
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import TrophyIcon from "@/svgs/trophy-icon";
import GenericCard from "@/components/card/GenericCard";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "CommunityChallenges",
  components: {PreviewImage, GenericCard, TrophyIcon, Swiper, SwiperSlide},
  data() {
    return {
      challenges: [],
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
    this.getChallenges();
  },
  methods: {
    getChallenges() {
      let data = {"dataId": null};
      getChallengesList(data)
        .then((response) => {
          this.challenges = response.data;
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
    dateFormat(date) {
      return moment(date).format("DD/MM/YYYY");
    },
    moveToDetail(id) {
      this.$router.push(`/challenge/detail/${id}`);
    },
    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl(imagePath) + "?optimizer=image&format=webp&sharpen=true" : DefaultImage;
    },
    getDatesDifference(startDate, endDate) {
      let a = moment(startDate);
      let b = moment(endDate);
      let result = b.diff(a, "days");
      return result === 0 ? 1 : result + 1;
    },
  }
};
</script>

<style scoped>
@import url('~@/assets/css/community-challenge.css');


::v-deep .section-body .card {
  width: 340px;
  height: 338px;
}

::v-deep .card .card-body {
  box-shadow: rgba(0, 0, 0, 0.1) 0px 22px 40px;
  border-radius: 11px;
  overflow: hidden;
}

@media screen and (max-width: 991px) {
  ::v-deep .section-body .card {
    width: 290px;
  }
}

::v-deep .section-body .card .card-body {
  height: 338px;
}
</style>