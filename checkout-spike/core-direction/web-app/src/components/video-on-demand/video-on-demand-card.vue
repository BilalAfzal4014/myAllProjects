<template>
  <div class="vod-card relative" @click="navigateToDetailPage">
    <div class="vod-img-box">
      <button class="vod-player-type-box">
        <img :src="`${videoCardIcon}`" alt="" class="vod-player-type-icon" height="12px"
             width="10px"
        >
      </button>
      <PreviewImage :alt="cardDetails.title" :src="cardDetails.thumbnail" classes="vod-img" type="cover" />
      <p class="vod-duration">
        <img alt="" class="vod-player-type-icon" src="@/assets/images/vod-standalone/Clock-Icon.svg">
        {{ convertToTimeFormat(cardDetails.duration.name) }}
      </p>
    </div>
    <div class="vod-title-box">
      <a class="vod-title" @click="navigateToDetailPage">{{ cardDetails.title }}</a>
    </div>
    <ul class="instructor-list">
      <li class="instructor-item">
        <a class="instructor-link" @click="navigateToDetailPage">{{ presenterName }}</a>
      </li>
    </ul>
  </div>
</template>

<script>
import VideoIcon from "@/assets/images/btn-play.svg";
import PodcastIcon from "@/assets/images/headphone.svg";
import DefaultImage from "@/assets/images/default_profile_img.png";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "VideoOnDemandCard",
  components: {PreviewImage},
  props: {
    cardDetails: {
      type: Object
    }
  },
  data() {
    return {
      videoCardIcon: this.cardDetails.content_type.code === "VIDEO" ? VideoIcon : PodcastIcon
    };
  },
  computed: {
    presenterName() {
      const firstName = this.cardDetails.presenter?.firstname;
      const lastName = this.cardDetails.presenter?.lastname;
      return `${firstName} ${lastName}`;
    }
  },
  methods: {
    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl(imagePath) : DefaultImage;
    },
    navigateToDetailPage() {

      this.$router.push(`/on-demand/detail/${this.cardDetails.id}`);
    },
    convertToTimeFormat(str) {
      let totalMinutes = parseInt(str.split(" ")[0]);
      let hours = Math.floor(totalMinutes / 60);
      let minutes = totalMinutes % 60;
      let seconds = 0;

      let hoursStr = hours < 10 ? "0" + hours : "" + hours;
      let minutesStr = minutes < 10 ? "0" + minutes : "" + minutes;
      let secondsStr = seconds < 10 ? "0" + seconds : "" + seconds;

      return hours > 0 ? `${hoursStr}:${minutesStr}:${secondsStr}` : `${minutesStr}:${secondsStr}`;
    }

  }
};
</script>

<style scoped>
.on-demand .vod-card {
  width: 295px;
  margin-right: 20px;
}

.section-body .swiper-container .swiper-wrapper .swiper-slide {
  min-height: unset !important;
  width: max-content !important;
}

.vod-card .vod-img-box {
  position: relative;
  border-radius: 8px;
  margin-bottom: 4px;
}

.vod-card .vod-img-box .vod-player-type-box {
  position: absolute;
  top: 8px;
  left: 8px;
  width: 32px;
  height: 32px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  background-color: #690FAD;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

.vod-card .vod-img-box .vod-img {
  width: 100%;
  height: 168px;
  -o-object-fit: cover;
  object-fit: cover;
  -o-object-position: center;
  object-position: center;
  border-radius: 8px;
}

.vod-card .vod-img-box .vod-duration {
  position: absolute;
  right: 8px;
  bottom: 8px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 4px;
  column-gap: 4px;
  padding: 4px;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  color: #fff;
  background: #06070E;
  border-radius: 4px;
}

.vod-card .vod-title-box {
  padding: 4px;
  margin-bottom: 3px;
}

.vod-card .vod-title-box .vod-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  text-overflow: ellipsis;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  display: -webkit-box;
  max-height: 34px;
  text-transform: uppercase;
  color: #06070E;
}

.vod-card .instructor-list {
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 2px;
  column-gap: 2px;
  row-gap: 2px;
  padding: 0 4px;
  text-overflow: ellipsis;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  display: -webkit-box;
  max-height: 23px;
}

.vod-card .instructor-list .instructor-item,
.vod-card .instructor-list .instructor-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  display: inline-block;
}
</style>