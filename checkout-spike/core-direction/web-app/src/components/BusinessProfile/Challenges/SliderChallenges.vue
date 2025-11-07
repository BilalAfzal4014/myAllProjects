
<template>
  <swiper v-if="sliderData.length > 0" class="swiper" :options="challengesOption">
    <swiper-slide v-for="(challenge,index) in sliderData" :key="index">
      <ChallengesCard :challengeTitle="challenge.title ?? ''" :challengeSlug="challenge.slug_url ?? ''" :challengeStartDate="challenge.start_date ?? ''" :challengeEndDate="challenge.end_date ?? ''" :challengeLogoUrl="challenge.logo ?? ''" :challengeCoverPhoto="challenge.cover_photo ?? ''" :userRank="challenge.rank" :totalParticipants="challenge.total_participants" />
    </swiper-slide>
    <swiper-slide>
      <div class="btn-package-detail-wrap">
        <a class="btn-browse-more" href="#challenges">
          <span> Browse more </span>
          <img src="@/assets/images/browse-more-icon.svg" alt="browse more">
        </a>
      </div>
    </swiper-slide>
    <div slot="pagination" class="swiper-pagination" />
  </swiper>
</template>
<script>
import { Swiper, SwiperSlide } from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import ChallengesCard from "./ChallengesCard.vue";
export default {
  name: "SliderActivities",
  components: {
    Swiper,
    SwiperSlide,
    ChallengesCard,
  },
  props: {
    sliderData: {
      type: Array,
      default: ()=>[],
    }
  },
  data() {
    return {
      challengesOption: {
        slidesPerView: 3,
        spaceBetween: 20,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        breakpoints: {
          1200: {
            slidesPerView: 3,
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
            slidesPerView: 1.48,
            spaceBetween: 20
          }
        }
      }
    };
  },
};
</script>

<style lang="scss" scoped>
  .swiper-slide{
    width: 440px !important;
    padding: 16px 0 32px;
    @media (max-width:480px)  {
      width: 310px !important;
      padding: 16px 0 20px;
    }
	}
	.swiper-container {
		overflow: inherit;
    margin-right: -200px;
    width: calc(100% + 200px);
	}
  .btn-browse-more{
    display: flex;
    align-items: center;
    justify-content: center;
    column-gap: 8px;
    width: 176px;
    height: 100%;
    min-height: 164px;
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
    @media (max-width:480px) {
      min-height: 125px;
    }
  }
  .swiper-pagination{
    display: none;
  }
  .btn-package-detail-wrap{
    background-color: #FFFFFA;
  }
</style>