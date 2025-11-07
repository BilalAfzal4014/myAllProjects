<template>
  <div id="descriptive-popup">
    <div v-if="showVideoPopUp" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div @click="showModal(false, false)">
                <CrossIcon />
              </div>
            </div>
            <div class="descriptive-popup-banner">
              <img
                :src="`${getImageUrl(cardDetails.thumbnail)}?optimizer=image&format=webp&width=1680&sharpen=true`"
                alt=""
                class="descriptive-popup-video"
              >
              <div
                class="form-container mx-auto flex items-center justify-between"
              >
                <div class="flex items-center">
                  <button
                    class="btn-play flex items-center"
                    @click="playVideo"
                  >
                    <img alt="" src="../../assets/images/btn-play.svg"> Play
                  </button>
                  <div @click="changeVideoStatus">
                    <FavoriteIcon :class="`${cardDetails.is_fvt ? 'active' : ''}`" />
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <div class="row flex items-start">
                  <div class="col-left">
                    <h4 class="modal-title">
                      {{ cardDetails.title }}
                    </h4>
                  </div>
                  <div class="col-right">
                    <div class="instructor-box">
                      <p class="tag flex">
                        <span class="tag-title">Presenter:</span>
                        <router-link :to="`profile/${cardDetails.presenter.username}`">
                          <span> {{ cardDetails.presenter.firstname }}
                            {{ cardDetails.presenter.lastname }} </span>
                        </router-link>
                      </p>
                    </div>
                    <div class="category-box">
                      <p class="tag flex">
                        <span class="tag-title">Tags:</span>
                        <span><a href="#">{{ cardDetails.category.name }}, </a>
                          <a href="#">{{
                            cardDetails.sub_category.name
                          }}</a></span>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="row flex items-start">
                  <div class="col-left">
                    <div class="requirement-box flex items-start items-center">
                      <p class="descriptive-popup-title">
                        Equipment Required
                      </p>
                      <div v-if="cardDetails.content_equipment.length" class="tags-requirement-box flex items-center">
                        <p
                          v-for="equipment in cardDetails.content_equipment"
                          :key="`equipment-item${equipment.equipment.id}`"
                          class="descriptive-popup-tag rounded-full"
                        >
                          {{ equipment.equipment.name }}
                        </p>
                      </div>
                      <p v-else>
                        No Equipment Required
                      </p>
                    </div>
                    <div class="descriptive-popup-info-box">
                      <p v-if="cardDetails.description.length > contentLength && !isShowAll">
                        {{ cardDetails.description.slice(0, contentLength) }} ... <span class="cursor-pointer"
                                                                                        href="#" style="color:#690fad;"
                                                                                        @click="isShowAll = true"
                        >read More</span>
                      </p>
                      <p v-else>
                        {{ cardDetails.description }}
                      </p>
                    </div>
                  </div>
                  <div class="col-right" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showIFrameModal" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box" style="background: #ffffffa1;">
            <div class="modal-header">
              <div @click="showModal(false, true)">
                <CrossIcon />
              </div>
            </div>
            <div class="player-modal" v-html="`${iFrameContent}`" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import DefaultImage from "@/assets/images/default_profile_img.png";
import CrossIcon from "@/svgs/video-on-demand/CrossIcon";
import FavoriteIcon from "@/svgs/video-on-demand/FavoriteIcon";
import {updateVideoStatus} from "@/apiManager/video-on-demand";
import * as toastr from "toastr";
import {VIDEO_STATUS} from "@/common/constants/constants";
import emitter from "tiny-emitter/instance";
import {updateCodCorePoints} from "@/apiManager/gamification";

export default {
  name: "VideoPopUp",
  components: {FavoriteIcon, CrossIcon},
  props: {
    cardDetails: {
      type: Object,
      default: null,
    },
    showVideoPopUp: {
      type: Boolean,
      default: null
    }
  },
  data() {
    return {
      showIFrameModal: false,
      iFrameContent: null,
      start: 0,
      end: 0,
      contentLength: 500,
      isShowAll: false,
    };
  },
  methods: {
    playVideo() {
      this.showModal(true, false);
      this.iFrameContent = this.cardDetails.content_source === "CDN" ? this.cardDetails.cdn_metadata.html_frame : this.cardDetails.external_content_meta;
      setTimeout(updateCodCorePoints(), 5000);
    },
    showModal(iFrameModalValue, videoModalValue) {
      this.isShowAll = false;
      this.showIFrameModal = iFrameModalValue;
      this.setShowVideoPop(videoModalValue);
    },
    setShowVideoPop(value) {
      this.$emit("setShowVideoPop", value);
    },
    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl(imagePath) : DefaultImage;
    },
    getChangeVideoStatusPayload() {
      return {
        contentId: this.cardDetails.id,
        status: this.cardDetails.is_fvt ? VIDEO_STATUS.UNFAVORITE : VIDEO_STATUS.FAVORITE
      };
    },
    changeVideoStatus() {
      updateVideoStatus(this.getChangeVideoStatusPayload()).then((response) => {
        this.cardDetails.is_fvt = !this.cardDetails.is_fvt;
        emitter.emit("fetch_favorites");
      })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },

  },
};
</script>

<style scoped>
.player-modal {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 500px;
  border-radius: 0;
}

iframe {
  width: 100%;
  max-width: 100% !important;
  height: 100%;
  border-radius: 0 !important;
}

.track-player {
  width: 100%;
  max-width: 100%;
  height: 500px;
}

.track-player.track-player--show-upsell {
  height: 500px;
  border-radius: 0;
  align-items: center;
  display: flex;
}

.track-player__logo-header {
  position: absolute;
  top: 20px;
  left: 20px;
  width: auto;
}

.track-player__artwork,
.dECpSYmWqPAhq6xqf2t3 {
  width: 100%;
  max-width: 180px;
  height: 180px;
  border-radius: 50%;
  overflow: hidden;
  margin-right: 3%;
  margin-left: 8%;
}

.track-player__header.metadata-lockup,
html[dir=ltr] .Vo5CZgetFW8OpvjS2Grw {
  width: 100%;
  margin-right: 8%;
  max-width: 477px;
}

.metadata-lockup__title-wrapper {
  align-items: flex-start;
}

embed-audio-controls {
  position: absolute;
  right: 20px;
  bottom: 2px;
}

.gMx7ZQAGhBSydDebRjyI {
  height: 500px;
  border-radius: 0;
}

.AcNBXY8a1dtuVjzohHum {
  height: 100%;
  align-items: center;
}

.HLXe2lxjDkJqhQi2vrEf {
  right: unset;
  top: 20px;
  left: 20px;
}

.wt-chart svg,
.wt-chart-active svg {
  display: none;
}

#descriptive-popup .modal-header {
  position: absolute;
  right: 0;
  z-index: 2;
  padding-top: 10px;
  padding-right: 20px;
}

@media (max-width: 767px) {
  #descriptive-popup .modal-header {
    padding: 10px;
  }
}

#descriptive-popup .descriptive-popup-banner {
  position: relative;
  width: 100%;
  min-height: 365px;
  max-height: 365px;
  border-radius: 11px 11px 0 0;
  -webkit-border-radius: 11px 11px 0 0;
  -moz-border-radius: 11px 11px 0 0;
  -ms-border-radius: 11px 11px 0px 0px;
  -o-border-radius: 11px 11px 0px 0px;
}

@media (max-width: 767px) {
  #descriptive-popup .descriptive-popup-banner {
    min-height: 250px;
    max-height: 250px;
  }
}

#descriptive-popup .descriptive-popup-banner::after {
  position: absolute;
  content: "";
  width: 100%;
  height: 365px;
  top: 0;
  left: 0;
  background: linear-gradient(
      68.27deg,
      rgba(255, 255, 255, 0) 89.88%,
      #ffffff 100.49%
  ),
  linear-gradient(180deg, rgba(255, 255, 255, 0) 49.91%, #ffffff 100.09%);
}

@media (max-width: 767px) {
  #descriptive-popup .descriptive-popup-banner::after {
    min-height: 250px;
    max-height: 250px;
  }
}

#descriptive-popup .descriptive-popup-banner .form-container {
  position: absolute;
  left: 0;
  right: 0;
  bottom: 30px;
  z-index: 2;
}

@media (max-width: 767px) {
  #descriptive-popup .descriptive-popup-banner .form-container {
    padding-left: 1.25rem;
    padding-right: 1.25rem;
    bottom: 10px;
  }
}

@media (max-width: 389px) {
  #descriptive-popup .descriptive-popup-banner .form-container {
    padding-left: 15px;
    padding-right: 15px;
  }
}

#descriptive-popup .descriptive-popup-video {
  width: 100%;
  min-height: 365px;
  max-height: 365px;
  -o-object-fit: cover;
  object-fit: cover;
}

@media (max-width: 767px) {
  #descriptive-popup .descriptive-popup-video {
    min-height: 250px;
    max-height: 250px;
  }
}

#descriptive-popup .modal-outer-box {
  position: relative;
  max-width: 840px;
  border-radius: 11px 11px 3px 3px;
}

#descriptive-popup .form-container {
  width: 100%;
  max-width: 720px;
}

#descriptive-popup .modal-body {
  background: #ffffff;
  padding-top: 18px;
  padding-bottom: 50px;
}

@media (max-width: 767px) {
  #descriptive-popup .modal-body {
    padding-top: 30px;
    padding-bottom: 30px;
  }
}

@media (max-width: 389px) {
  #descriptive-popup .modal-body {
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 0;
  }
}

#descriptive-popup .btn-play {
  background-color: #690fad;
  color: #ffffff;
  font-family: Montserrat;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  padding: 13px 28px;
  border-radius: 11px;
  margin-right: 31px;
}

#descriptive-popup .btn-play img,
#descriptive-popup .btn-play svg {
  margin-right: 13px;
}

@media (max-width: 389px) {
  #descriptive-popup .btn-play {
    margin-right: 10px;
  }

  #descriptive-popup .btn-play img,
  #descriptive-popup .btn-play svg {
    margin-right: 10px;
    max-height: 16px;
  }
}

#descriptive-popup .btn-volume {
  padding: 8px;
  width: 44px;
  height: 44px;
  border: 1px solid #690fad;
}

#descriptive-popup .btn-volume .show {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}

#descriptive-popup .btn-volume .hide {
  display: none;
}

#descriptive-popup .row {
  row-gap: 25px;
  margin-bottom: 30px;
  -webkit-column-gap: 6px;
  column-gap: 6px;
}

@media (max-width: 767px) {
  #descriptive-popup .row {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }
}

#descriptive-popup .col-left {
  width: 100%;
}

@media (min-width: 768px) {
  #descriptive-popup .col-left {
    max-width: 480px;
  }
}

#descriptive-popup .col-right {
  width: 100%;
}

@media (min-width: 768px) {
  #descriptive-popup .col-right {
    max-width: 243px;
  }
}

#descriptive-popup .modal-title {
  font-family: "Montserrat", sans-serif;
  font-size: 24px;
  font-weight: 600;
  line-height: 29px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 16px;
}

#descriptive-popup .requirement-box {
  row-gap: 10px;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  margin-bottom: 30px;
}

@media (max-width: 767px) {
  #descriptive-popup .requirement-box {
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
  }
}

#descriptive-popup .descriptive-popup-title {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0;
  text-align: left;
  margin-right: 10px;
  min-width: 153px;
}

#descriptive-popup .descriptive-popup-tag {
  font-family: "Montserrat", sans-serif;
  font-size: 10px;
  font-weight: 600;
  line-height: 12px;
  letter-spacing: 0;
  text-align: center;
  color: #ffffff;
  background-color: #690fad;
  padding: 5px 10px;
}

#descriptive-popup .descriptive-popup-info-box .descriptive-popup-title {
  margin-bottom: 15px;
}

#descriptive-popup .descriptive-popup-info {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 15px;
}

#descriptive-popup .instructor-box {
  margin-bottom: 25px;
}

@media (max-width: 767px) {
  #descriptive-popup .instructor-box {
    margin-bottom: 15px;
  }
}

#descriptive-popup .tag {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 18px;
  letter-spacing: 0;
  text-align: left;
}

#descriptive-popup .tag a {
  font-weight: 600;
}

#descriptive-popup .tags-requirement-box {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

#descriptive-popup .tag-title {
  min-width: 62px;
  margin-right: 2px;
}
</style>