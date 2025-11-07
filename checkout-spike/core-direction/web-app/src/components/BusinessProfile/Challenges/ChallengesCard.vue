<template>
  <div class="challenges-card" @click="goToChellenge">
    <div class="card-body">
      <div class="logo-box">
        <img :src="activitiesBaseUrl+challengeLogoUrl+'?optimizer=image&format=webp'" width="64" height="64" alt="" class="logo">
      </div>
      <div class="content-box">
        <p class="challenge-title">
          {{ challengeTitle }}
        </p>
        <p class="challenge-date">
          <span class="start-date">{{ new Date(challengeStartDate).toLocaleDateString('en-GB') }}</span>
          <span class="divider">-</span>
          <span class="end-date">{{ new Date(challengeEndDate).toLocaleDateString('en-GB') }}</span>
        </p>
        <div class="challenge-participants-box">
          <img src="@/assets/images/challenge-participants-icon.svg" alt="" class="challenge-participants-icon">
          <p class="challenge-participants">
            <span class="active-participants">{{ userRank }}</span>
            <span class="divider">/</span>
            <span class="all-participants"> {{ totalParticipants }} </span>
          </p>
        </div>
      </div>
    </div>
    <div class="card-header">
      <img :src="activitiesBaseUrl+challengeCoverPhoto+'?optimizer=image&format=webp'" alt="" class="card-img">
    </div>
  </div>
</template>

<script>
import { BASE_URLS } from "@/common/constants/constants";
export default {
  name: "ChallengesCard",
  props: {
    challengeTitle: {
      type: String,
      default: "",
    },
    challengeSlug: {
      type: String,
      default: "",
    },
    challengeStartDate: {
      type: String,
      default: "",
    },
    challengeEndDate: {
      type: String,
      default: "",
    },
    challengeLogoUrl: {
      type: String,
      default: "",
    },
    challengeCoverPhoto: {
      type: String,
      default: "",
    },
    userRank:{
      type: Number,
      default: 0,
    },
    totalParticipants:{
      type: Number,
      default: 0,
    },
   
  },
  computed: {
    activitiesBaseUrl(){
		 return BASE_URLS.ASSETS_MEDIA_MICRO_SERVICE_PREFIX;
    },
  },
  methods: {
    goToChellenge(){
      return this.$router.push(`/challenge/detail/${this.challengeSlug}`);
    }
  }
};
</script>

<style lang="scss" scoped>
  .challenges-card{
    display: flex;
    align-items: center;
    position: relative;
    background: #FFFFFA;
    box-shadow: 0px 12px 16px rgba(28, 4, 47, 0.08), 0px 4px 6px rgba(0, 0, 0, 0.03);
    border-radius: 12px;
    cursor: pointer;
    padding: 47px 32px;
    @media (max-width:767px){
      padding: 30px 32px 26px;
    }
    @media (max-width:480px){
      width: 310px;
    }
    overflow: hidden;
    &::after {
      background: -webkit-gradient(linear, left top, right top, from(#FFFFFF), color-stop(99.97%, rgba(255, 255, 255, 0)));
      background: linear-gradient(90deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 99.97%);
      mix-blend-mode: normal;
      border-radius: 0px;
      width: 190px;
      height: 100%;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      content: "";
    }
    .card-body{
      display: flex;
      align-items: center;
      column-gap: 16px;
      position: relative;
      z-index: 1;
      .logo-box{
        border-radius: 50%;
        -webkit-border-radius: 50%;
        -moz-border-radius: 50%;
        -ms-border-radius: 50%;
        -o-border-radius: 50%;
        overflow: hidden;
        min-width: 66px;
        min-height: 66px;
        max-width: 66px;
        max-height: 66px;
        box-shadow: 0px 0px 4px rgba(0, 0, 0, 0.25);
        .logo{
          aspect-ratio: 1/1;
          width: 66px;
          height: 66px;
          object-fit: cover;
          object-position: center;
        }
      }
      .content-box{
        display: flex;
        flex-direction: column;
        row-gap: 8px;
        @media (max-width:767px){
          row-gap: 4px;
        }
        .challenge-title{
          font-family: 'Montserrat', sans-serif;
          font-size: 16px;
          font-weight: 700;
          line-height: 20px;
          letter-spacing: 0em;
          text-align: left;
          color: #06070E;
          @media (max-width:767px){
            font-size: 12px;
            line-height: 15px;
            min-height: 30px;
          }
        }
        .challenge-date{
          font-family: 'Montserrat', sans-serif;
          font-size: 14px;
          font-weight: 400;
          line-height: 17px;
          letter-spacing: 0em;
          text-align: left;
          color: #06070E;
          display: flex;
          align-items: center;
          column-gap: 4px;
          @media (max-width:767px){
            font-size: 12px;
            line-height: 15px;
          }
        }
        .challenge-participants-box{
          display: flex;
          align-items: center;
          column-gap: 5px;
          .challenge-participants{
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 17px;
            letter-spacing: 0em;
            text-align: left;
            color: #06070E;
            display: flex;
            align-items: center;
            column-gap: 4px;
            @media (max-width:767px){
              font-size: 12px;
              line-height: 15px;
            }
            .active-participants{
              font-weight: 700;
            }
          }
        }
      }
    }
    .card-header{
      background: linear-gradient(90deg, #FFFFFF 0%, rgba(255, 255, 255, 0) 99.97%);
      mix-blend-mode: normal;
      border-radius: 0px;
      width: 190px;
      height: 100%;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      .card-img{
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
      }
    }
  }

</style>