<template>
  <div id="business-profile">
    <div v-if="challengesData.length" class="challenge-box custom-container">
      <div v-for="(challenge, index) in challengesData" :key="index" class="card challenge-card relative"
           @click="moveToDetail(challenge.slug_url)"
      >
        <div class="card-body">
          <PreviewImage :src="challenge.cover_photo" alt="Challenge cover photo"
                        class="card-img rounded-t-lg overflow-hidden"
                        type="cover"
          />
          <div class="gym-logo rounded-full absolute top-20 left-6">
            <PreviewImage :src="challenge.logo" alt="Challenge logo photo" class="rounded-full h-16 w-16" type="logo" />
          </div>
          <div class="card-info-box  flex justify-between px-4 pt-8 pb-4 rounded-b-lg">
            <div class="gym-info w-full">
              <a class="card-title" href="#"> {{ challenge.title }}</a>
              <div class="grid grid-cols-8 gap-1 items-end">
                <ul class="float-left challenge-info-list flex flex-col col-span-6">
                  <li class="challenge-info-item flex items-center">
                    <span class="challenge-info-label">Start Date: </span>
                    <span class="challenge-info-value"> {{ dateFormat(challenge.start_date) }} </span>
                  </li>
                  <li class="challenge-info-item flex items-center">
                    <span class="challenge-info-label">Duration:</span>
                    <span class="challenge-info-value">
                      {{ getDatesDifference(challenge.start_date, challenge.end_date) }} Days
                    </span>
                  </li>
                </ul>
                <div class="float-right col-span-2">
                  <button
                    class="btn-view-challengers ml-auto rounded-full flex items-center justify-center"
                  >
                    <challenge-icon />
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <search-friend-pagination v-if="challengesData.length" :count="total" :limit="data.limit"
                              :offset="data.offset"
                              @fetch-data="getChallenges"
    />
  </div>
</template>

<script>
import {getBusinessChallenges} from "@/apiManager/gamification";
import * as toastr from "toastr";
import moment from "moment/moment";
import DefaultImage from "@/assets/images/default_profile_img.png";
import SearchFriendPagination from "@/partials/SearchFriendPagination";
import ChallengeIcon from "@/svgs/challenge-icon";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "GroupChallenges",
  data() {
    return {
      challengesData: [],
      total: 0,
      data: {
        profileId: null,
        limit: 10,
        offset: 0
      }
    };
  },
  watch: {
    companyId(newValue) {
      if (newValue && this.$store.state.token) {
        this.getChallenges();
      }
    }
  },
  props: {
    companyId: {
      type: Number,
      required: false,
    }
  },
  components: {PreviewImage, ChallengeIcon, SearchFriendPagination},
  beforeMount() {
    if (this.companyId && this.$store.state.token) {
      this.getChallenges();
    }

  },
  methods: {
    getChallengesPayload() {
      return {
        ...this.data,
        profileId: this.companyId,
      };
    },
    async getChallenges() {
      try {
        const response = await getBusinessChallenges(this.getChallengesPayload());
        this.challengesData = response.data.challenges;
        this.total = response.data.totalCount;
      } catch (error) {
        this.handleError(error);
      }
    },
    handleError(error) {
      let errorMsg = "An error occurred";
      if (error && error[0] && error[0].response && error[0].response.data && error[0].response.data.errors && error[0].response.data.errors.length > 0) {
        errorMsg = error[0].response.data.errors[0].error;
      }
      toastr.error(errorMsg);
    },
    dateFormat(date) {
      return moment(date).format("DD/MM/YYYY");
    },
    getDatesDifference(startDateStr, endDateStr) {
      const startDate = new Date(startDateStr);
      const endDate = new Date(endDateStr);
      const oneDayInMs = 1000 * 60 * 60 * 24;
      const timeDiffInMs = endDate.getTime() - startDate.getTime();
      const diffInDays = Math.ceil(timeDiffInMs / oneDayInMs);
      return diffInDays || 1;
    },
    getImageUrl(imagePath, type) {
      const url = imagePath
        ? `${imagePath}?optimizer=image&format=webp&sharpen=true&width=${
          type === "logo" ? "150" : "680"
        }`
        : DefaultImage;
      return this.constants.getImageUrl(url);
    },
    moveToDetail(id) {
      this.$router.push(`/challenge/detail/${id}`);
    }
  }
};
</script>

<style scoped>

.no-challenge {
  text-align: center;
  margin: auto;
  display: block;
}

#business-profile {
  background-color: #FFFFFF;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-header .custom-container {
    padding: 0;
  }
}

.challenge-box {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(288px, 1fr));
  column-gap: 30px;
  row-gap: 30px;
}

@media screen and (max-width: 767px) {
  .challenge-box {
    margin-top: 60px;
  }
}

.card .card-body {
  box-shadow: rgba(0, 0, 0, 0.1) 0px 22px 40px;
  border-radius: 11px;
  overflow: hidden;
}

#business-profile .business-profile-header .business-profile-banner-box,
#business-profile .business-profile-header .business-profile-map-box {
  width: 100%;
  border-radius: 0 0 7px 7px;
  height: 331px;
  overflow: hidden;
}

#business-profile .business-profile-header .business-profile-banner-box img,
#business-profile .business-profile-header .business-profile-map-box img {
  width: 100%;
  border-radius: 0 0 7px 7px;
  height: 331px;
  -o-object-fit: cover;
  object-fit: cover;
  -o-object-position: center;
  object-position: center;
}

@media screen and (max-width: 767px) {
  #business-profile .business-profile-header .business-profile-banner-box,
  #business-profile .business-profile-header .business-profile-map-box,
  #business-profile .business-profile-header .business-profile-banner-box img,
  #business-profile .business-profile-header .business-profile-map-box img {
    height: 109px;
  }
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-header .business-profile-map-box {
    display: none;
  }
}

#business-profile .business-profile-info-box {
  margin-top: -8px;
  padding-bottom: 23px;
}

#business-profile .business-profile-info-box .business-profile-short-info-box {
  padding-bottom: 15px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-short-info-box {
    padding-bottom: 30px;
  }
}

#business-profile .business-profile-info-box .business-profile-logo-box {
  margin-right: 9px;
  padding: 3px;
  outline: 3px solid #fff;
}

#business-profile .business-profile-info-box .business-profile-logo-box img {
  min-width: 110px;
  max-width: 110px;
  min-height: 110px;
  max-height: 110px;
  outline: 4px solid #690FAD;
  border: 3px solid #FFFFFF;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-logo-box img {
    min-width: 75px;
    max-width: 75px;
    min-height: 75px;
    max-height: 75px;
  }
}

#business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 500;
  line-height: 28.13px;
  margin-bottom: 4px;
  width: 100%;
  max-width: 349px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-title {
    font-size: 18px;
    padding-top: 10px;
    padding-bottom: 10px;
    margin: 0;
  }
}

#business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17.07px;
  width: 100%;
  max-width: 349px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-subtitle {
    font-size: 12px;
  }
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-contact-info-box {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-column-gap: 12px;
    column-gap: 12px;
    row-gap: 12px;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number,
  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-map,
  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-business-map {
    margin: 0px !important;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number,
  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-map {
    font-size: 10px !important;
    line-height: 12px !important;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-business-map svg {
    max-width: 40px;
    max-height: 40px;
  }
}

#business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number,
#business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-map {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 14.63px;
}

#business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number {
  padding: 13px 16px;
  background-color: #FFFFFF;
  margin-right: 15px;
  -webkit-box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
}

#business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number svg,
#business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number img {
  margin-right: 7.1px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number {
    padding: 10px 16px 10px 14px;
  }
}

#business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-map {
  padding: 16px 27px;
  color: #F1F1F1;
  background-color: #690FAD;
  margin-right: 14px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-map {
    padding: 13px 22px 15px;
  }
}

#business-profile .tab-container {
  border-top: 1px solid #F1F1F1;
  border-bottom: 1px solid rgba(0, 0, 0, 0.27);
  margin-bottom: 60px;
}

#business-profile .business-profile-menu-tab-swiper-container {
  max-width: 927px;
}

@media screen and (min-width: 992px) {
  #business-profile .business-profile-menu-tab-swiper-container {
    -webkit-box-pack: justify;
    -ms-flex-pack: justify;
    justify-content: space-between;
  }
}

#business-profile .business-profile-menu-tab-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17.07px;
  margin-right: 15px;
  background-color: #FFFFFF;
  color: #000000;
  padding: 13.28px 29.13px 14.01px;
  border-bottom: 2px solid transparent;
  cursor: pointer;
  height: 48px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-menu-tab-item {
    padding: 13.28px 18.13px 14.01px;
  }
}

#business-profile .business-profile-menu-tab-item svg,
#business-profile .business-profile-menu-tab-item img {
  margin-right: 4.8px;
}

#business-profile .business-profile-menu-tab-item svg,
#business-profile .business-profile-menu-tab-item path {
  fill: #000000;
}

#business-profile .business-profile-menu-tab-item:hover {
  border-color: #690FAD;
  color: #690FAD;
}

#business-profile .business-profile-menu-tab-item:hover svg,
#business-profile .business-profile-menu-tab-item:hover path {
  fill: #690FAD;
}

#business-profile .active {
  border-color: #690FAD;
  color: #690FAD;
}

#business-profile .active svg,
#business-profile .active path {
  fill: #690FAD;
}

#business-profile .business-profile-gallery-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(183px, 183px));
  -webkit-column-gap: 15px;
  column-gap: 15px;
  row-gap: 15px;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  padding-bottom: 102px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-gallery-list {
    grid-template-columns: repeat(auto-fill, minmax(172px, 172px));
    -webkit-column-gap: 14px;
    column-gap: 14px;
    row-gap: 14px;
  }
}

#business-profile .business-profile-gallery-list .business-profile-gallery-item img {
  border-radius: 11px;
}

#business-profile .business-profile-info-box .business-profile-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  line-height: 21.94px;
}

#business-profile .business-profile-info-box .business-profile-about-us-box {
  margin-bottom: 35px;
}

#business-profile .business-profile-info-box .business-profile-about-us-box .business-profile-title {
  margin-bottom: 24px;
}

#business-profile .business-profile-info-box .business-profile-about-us-box .business-profile-about-us-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 24px;
  margin-bottom: 5px;
}

#business-profile .business-profile-info-box .what-offer-list {
  margin-bottom: 26px;
  margin-top: 21px;
}

#business-profile .business-profile-info-box .what-offer-item {
  width: 160px;
  height: 45px;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 14.06px;
  background-color: #690FAD;
  color: #F1F1F1;
  padding: 14px 20px;
  cursor: pointer;
  margin-right: 11px;
  margin-bottom: 11px;
}

#business-profile .business-profile-info-box .what-offer-item svg,
#business-profile .business-profile-info-box .what-offer-item img {
  min-width: 17px;
  max-width: 18px;
  margin-right: 18px;
}

#business-profile .business-profile-info-box .business-profile-address-box {
  margin-bottom: 84px;
}

@media screen and (min-width: 992px) {
  #business-profile .business-profile-info-box .business-profile-address-box {
    margin-bottom: 63px;
  }
}

#business-profile .business-profile-info-box .btn-business-address {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 26px;
  background-color: #FFFFFF;
  padding: 15px 20px;
  cursor: pointer;
  -webkit-box-shadow: 0px 2px 4px 0px #0000001A;
  box-shadow: 0px 2px 4px 0px #0000001A;
  margin-top: 21px;
  text-align: left;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-info-box .btn-business-address {
    font-size: 14px;
  }
}

#business-profile .business-profile-info-box .btn-business-address svg,
#business-profile .business-profile-info-box .btn-business-address img {
  margin-right: 10px;
  min-width: 26.43px;
}

#business-profile .business-profile-info-box .lg-business-profile-contact-info-box {
  margin-bottom: 64px;
}

@media screen and (min-width: 992px) {
  #business-profile .business-profile-info-box .lg-business-profile-contact-info-box {
    display: none;
  }
}

#business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-number,
#business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-map {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 14.63px;
}

#business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-number {
  padding: 13px 16px;
  background-color: #FFFFFF;
  margin-right: 15px;
}

#business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-number svg,
#business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-number img {
  margin-right: 7.1px;
}

#business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-map {
  padding: 16px 27px;
  color: #F1F1F1;
  background-color: #690FAD;
}

#business-profile .business-profile-activities-page {
  padding-bottom: 126px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter {
  margin-top: 30px;
  margin-bottom: 42px;
  margin-left: -1rem;
  margin-right: -1rem;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box {
  width: 100%;
  max-width: 1173px;
  padding: 42px 72px;
  -webkit-box-shadow: 0px 22px 40px 0px #0000001A;
  box-shadow: 0px 22px 40px 0px #0000001A;
  background-color: #FFFFFF;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box {
    padding: 29px 16px calc(30px - 1rem) 19px;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box {
  width: 100%;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box,
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box,
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box {
    margin-bottom: 1rem;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box input,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box select,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box input,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box select,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box input,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box select {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  line-height: 18.5px;
  font-weight: 400;
  width: 100%;
  padding: 11px 13px 12px 18px;
  display: inline-block;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
  padding-right: 28px;
  background-color: #F1F1F1;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box {
  max-width: 295px;
  position: relative;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box {
    max-width: calc(50% - .5rem);
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box svg {
  position: absolute;
  right: 13px;
  top: 13px;
  cursor: pointer;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box {
  display: none;
  position: absolute;
  width: 100%;
  top: 0;
  -webkit-box-shadow: 0px 4px 4px rgba(18, 18, 18, 0.25);
  box-shadow: 0px 4px 4px rgba(18, 18, 18, 0.25);
  border-radius: 20px 20px 0 0;
  -webkit-border-radius: 20px 20px 0 0;
  -moz-border-radius: 20px 20px 0 0;
  -ms-border-radius: 20px 20px 0 0;
  -o-border-radius: 20px 20px 0 0;
  z-index: 2;
  background-color: #FFFFFF;
}

@media screen and (min-width: 992px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box {
    left: 0;
    top: 2.9rem;
    border-radius: 0;
  }
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box {
    width: calc(100% + 66px);
    top: 2.9rem;
    right: 0;
    border-radius: 0;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body {
  padding-top: 17px;
  padding-bottom: 17px;
  padding-left: 8px;
  padding-right: 8px;
  margin-left: 10px;
  margin-right: 10px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list {
  height: 100%;
  max-height: 193px;
  overflow-y: scroll;
  padding-top: 5px;
  padding-bottom: 5px;
  /* width */
  /* Track */
  /* Handle */
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list::-webkit-scrollbar {
  width: 7px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list::-webkit-scrollbar-track {
  background: #F1F1F1;
  border-radius: 11px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.45);
  border-radius: 11px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list .location-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 18.75px;
  margin-bottom: 19px;
  cursor: pointer;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box .manual-location-field-body .location-list .location-item:last-child {
  margin-bottom: 0px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box {
  max-width: 295px;
  position: relative;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box {
    max-width: calc(50% - .5rem);
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box select {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box svg {
  position: absolute;
  right: 13px;
  top: 17px;
  cursor: pointer;
  background-color: transparent;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box svg,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box path {
  fill: #222222;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .svg-category-icon.active {
  transform: rotate(180deg);
  -webkit-transform: rotate(180deg);
  -moz-transform: rotate(180deg);
  -ms-transform: rotate(180deg);
  -o-transform: rotate(180deg);
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box {
  display: none;
  position: absolute;
  width: 100%;
  top: 0;
  -webkit-box-shadow: 0px 4px 4px rgba(18, 18, 18, 0.25);
  box-shadow: 0px 4px 4px rgba(18, 18, 18, 0.25);
  border-radius: 20px 20px 0 0;
  -webkit-border-radius: 20px 20px 0 0;
  -moz-border-radius: 20px 20px 0 0;
  -ms-border-radius: 20px 20px 0 0;
  -o-border-radius: 20px 20px 0 0;
  z-index: 2;
  background-color: #FFFFFF;
}

@media screen and (min-width: 992px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box {
    left: 0;
    top: 2.9rem;
    border-radius: 0;
  }
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box {
    left: 0;
    border-radius: 0;
    width: calc(100% + 66px);
    top: 2.9rem;
    border-radius: 0px;
    -webkit-border-radius: 0px;
    -moz-border-radius: 0px;
    -ms-border-radius: 0px;
    -o-border-radius: 0px;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body {
  padding-top: 17px;
  padding-bottom: 17px;
  padding-left: 8px;
  padding-right: 8px;
  margin-left: 10px;
  margin-right: 10px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body {
    border-top: none;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list {
  padding-top: 5px;
  padding-bottom: 5px;
  height: 100%;
  max-height: 193px;
  overflow-y: scroll;
  /* width */
  /* Track */
  /* Handle */
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list::-webkit-scrollbar {
  width: 7px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list::-webkit-scrollbar-track {
  background: #F1F1F1;
  border-radius: 11px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.45);
  border-radius: 11px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list .category-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 18.75px;
  margin-bottom: 19px;
  cursor: pointer;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box .category-field-body .category-list .category-item:last-child {
  margin-bottom: 0px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box {
  max-width: 256px;
  position: relative;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box {
    max-width: calc(65% - .5rem);
    margin-right: 0;
    margin-left: 0;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box svg {
  position: absolute;
  right: 13px;
  top: 13px;
  cursor: pointer;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box svg,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box path {
  fill: #000000;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box {
  display: none;
  position: absolute;
  width: 100%;
  -webkit-box-shadow: 0 4px 4px rgba(18, 18, 18, 0.25);
  box-shadow: 0 4px 4px rgba(18, 18, 18, 0.25);
  z-index: 2;
  background-color: #FFFFFF;
  width: calc(100% + 75px);
}

@media screen and (min-width: 992px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box {
    right: 0;
    top: 2.9rem;
    border-radius: 0;
  }
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box {
    top: 2.9rem;
    left: 0;
    border-radius: 0;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-header {
  padding: 32px 27px 32px 32px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-header .selected-calendar-from,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-header .selected-calendar-to {
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  line-height: 13.41px;
  font-weight: 400;
  color: #000000;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-filter {
  height: 215px;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body {
    border-top: none;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar {
  color: #000000;
  background-color: #FFFFFF;
  padding-bottom: 25px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar .current-calendar-info-box {
  padding-left: 32px;
  padding-right: 27px;
  margin-bottom: 17px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar .selected-calendar-month,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar .selected-calendar-year {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  line-height: 29.26px;
  font-weight: 400;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar table {
  width: calc(100% - 40px);
  margin-left: auto;
  margin-right: 19px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar table tr th,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar table tr td {
  font-family: 'Montserrat', sans-serif;
  font-size: 11px;
  line-height: 13.41px;
  font-weight: 400;
  padding-bottom: 15px;
  cursor: pointer;
  text-align: center;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-field-body .calendar table tr:last-child td {
  padding-bottom: 0;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-footer {
  margin-top: 23px;
  padding: 21px 31px 22px;
  -webkit-box-shadow: 0px -4px 4px 0px #0000001A;
  box-shadow: 0px -4px 4px 0px #0000001A;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-footer .btn-calendar-cancel,
#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-footer .btn-calendar-apply {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  line-height: 14.63px;
  font-weight: 500;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-footer .btn-calendar-cancel {
  color: #000000;
  margin-right: 31px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box .calendar-field-box .calendar-footer .btn-calendar-apply {
  color: #690FAD;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .search-btn-box {
  max-width: 122.59px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  width: 100%;
}

@media screen and (max-width: 991px) {
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .search-btn-box {
    max-width: calc(35% - .5rem);
    margin-left: auto;
  }
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .search-btn-box button {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  line-height: 18.5px;
  font-weight: 500;
  width: 100%;
  padding: 11px 13px;
  color: #FFFFFF;
  background-color: #690FAD;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .search-btn-box .btn-map {
  min-width: 41px;
  min-height: 42px;
  max-width: 41px;
  max-height: 42px;
  background-color: #F1F1F1;
  margin-left: 17.41px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  border-radius: 7px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .form-group label {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  line-height: 18.75px;
  font-weight: 400;
  color: #000000;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .form-group label::before {
  -webkit-filter: invert(0%) sepia(100%) saturate(0%) hue-rotate(21deg) brightness(97%) contrast(103%);
  filter: invert(0%) sepia(100%) saturate(0%) hue-rotate(21deg) brightness(97%) contrast(103%);
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .form-group input {
  display: none;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .form-group input:checked + label:after {
  top: 0px;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .form-group:hover label {
  color: #690FAD;
}

#business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .form-group:hover label::before {
  -webkit-filter: unset;
  filter: unset;
}

#business-profile .business-profile-activities-page .booking-card-box {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(288px, 1fr));
  row-gap: 30px;
  -webkit-column-gap: 30px;
  column-gap: 30px;
}

#business-profile .business-profile-packages-box {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 362px));
  -webkit-column-gap: 15px;
  column-gap: 15px;
  row-gap: 15px;
  padding-bottom: 102px;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
}

#business-profile .applied-filter-box {
  margin-bottom: 41px;
}

@media screen and (max-width: 767px) {
  #business-profile .applied-filter-box {
    margin-bottom: 30px;
  }
}

#business-profile .applied-filter-header {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  margin-bottom: 29px;
}

@media screen and (max-width: 767px) {
  #business-profile .applied-filter-header {
    margin-bottom: 31px;
  }
}

#business-profile .applied-filter-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: left;
  color: #000000;
}

@media screen and (max-width: 767px) {
  #business-profile .applied-filter-title {
    font-size: 16px;
    font-weight: 500;
    line-height: 19px;
  }
}

#business-profile .applied-filter-btn-reset {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 700;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: right;
  color: #690FAD;
  cursor: pointer;
}

@media screen and (max-width: 767px) {
  #business-profile .applied-filter-btn-reset {
    font-size: 16px;
    font-weight: 500;
    line-height: 19px;
  }
}

#business-profile .applied-filter-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: start;
  -ms-flex-pack: start;
  justify-content: flex-start;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 9px;
  column-gap: 9px;
  row-gap: 29px;
}

@media screen and (max-width: 767px) {
  #business-profile .applied-filter-list {
    -webkit-column-gap: 10px;
    column-gap: 10px;
    row-gap: 15px;
  }
}

#business-profile .applied-filter-item {
  min-width: 103px;
  display: inline-block;
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  padding: 14px 27px 13px 26px;
  background-color: #690FAD;
  color: #FFFFFF;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  position: relative;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  transition: .3s ease-in-out;
  -webkit-transition: .3s ease-in-out;
  -moz-transition: .3s ease-in-out;
  -ms-transition: .3s ease-in-out;
  -o-transition: .3s ease-in-out;
}

@media screen and (max-width: 767px) {
  #business-profile .applied-filter-item {
    font-size: 12px;
    line-height: 15px;
    padding: 14px 20px;
  }
}

#business-profile .applied-filter-item .btn-remove {
  cursor: pointer;
  position: absolute;
  right: 10px;
  top: -10px;
}

@media screen and (min-width: 768px) {
  #business-profile .applied-filter-item .btn-remove {
    display: none;
  }
}

#business-profile .applied-filter-item:hover {
  -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.25);
}

@media screen and (min-width: 768px) {
  #business-profile .applied-filter-item:hover .btn-remove {
    display: block;
  }
}

@media (max-width: 389px) {
  #business-profile .business-profile-info-box .business-profile-contact-info-box {
    -webkit-column-gap: 10px;
    column-gap: 10px;
    row-gap: 10px;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number svg,
  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number img {
    max-height: 16px;
    margin-right: 3px;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-number {
    padding: 9px 10px;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-brand-map {
    padding: 11px 10px;
  }

  #business-profile .business-profile-info-box .business-profile-contact-info-box .btn-business-map svg {
    max-width: 34px;
    max-height: 34px;
  }

  #business-profile .business-profile-menu-tab-item {
    margin-right: 10px;
    padding: 13.28px 8.13px 14.01px;
    font-size: 13px;
  }

  #business-profile .what-offer-item {
    width: 150px;
    height: 40px;
    font-size: 10px;
    margin-right: 10px;
    margin-bottom: 10px;
  }

  #business-profile .business-profile-info-box .btn-business-address {
    font-size: 12px;
    padding: 10px;
    line-height: 17px;
  }

  #business-profile .business-profile-info-box .business-profile-address-box {
    margin-bottom: 50px;
  }

  #business-profile .business-profile-info-box .lg-business-profile-contact-info-box {
    margin-bottom: 0;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    row-gap: 10px;
  }

  #business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-number {
    padding: 10px;
    background-color: #FFFFFF;
    margin-right: 10px;
  }

  #business-profile .business-profile-info-box .lg-business-profile-contact-info-box .btn-brand-map {
    padding: 10px;
  }

  #business-profile .business-profile-activities-page .booking-card-box {
    row-gap: 20px;
  }

  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box,
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box {
    max-width: 100%;
    margin-right: 0;
  }

  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-category-box .category-field-box {
    width: 100%;
    border-radius: 11px 11px 20px 20px;
  }

  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-search-box .manual-location-field-box {
    width: 100%;
    border-radius: 11px 11px 20px 20px;
  }

  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .input-field-date-box,
  #business-profile .business-profile-activities-page .business-profile-activities-filter .filter-box .search-btn-box {
    max-width: 100%;
  }

  #business-profile .applied-filter-item {
    padding: 10px;
  }

  #business-profile .business-profile-info-box .business-profile-short-info-box {
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
  }

  #business-profile .business-profile-info-box .business-profile-name-box .business-profile-brand-title {
    font-size: 16px;
    line-height: 23.13px;
    padding-bottom: 0;
  }

  #business-profile .business-profile-gallery-list {
    grid-template-columns: repeat(auto-fill, minmax(139px, 1fr));
    row-gap: 10px;
    -webkit-column-gap: 10px;
    column-gap: 10px;
  }

  #business-profile .business-profile-gallery-item img {
    display: block;
    width: 100%;
  }
}

.challenge-card .card-info-box {
  padding: 18px 18px 23px;
}

.card .card-body .card-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  color: #000000;
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  text-align: left;
  width: 100%;
  padding-top: 10px;
  margin: 0 0 10px !important;
  width: -webkit-fit-content;
  width: -moz-fit-content;
  width: fit-content;
  max-width: -webkit-fill-available;
}

.challenge-card .challenge-info-list {
  row-gap: 7px;
}

.challenge-card .challenge-info-label {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  margin-right: 5px;
}

.challenge-card .challenge-info-value {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
}

.challenge-card .btn-view-challengers {
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #690FAD;
}

#business-profile .challenge-box {
  padding-bottom: 50px;
}
</style>