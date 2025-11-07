<template>
  <main id="main">
    <content-sharing-modal v-if="isShowShareModal" />
    <section id="vod-standalone">
      <div class="container-fluid px-5">
        <div class="player-box" v-html="iframeHtml" />
        <div class="content-box">
          <div class="video-content-box">
            <p class="video-title">
              {{ cardDetails?.content?.title }}
            </p>
            <ul class="like-share-list">
              <li class="like-share-item">
                <button :class="buttonClass"
                        @click="toggleFavouriteStatus"
                >
                  <svg fill="none" height="18"
                       viewBox="0 0 20 18"
                       width="20"
                       xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M19.5425 5.99771C19.5425 9.07905 17.1199 11.0489 14.9151 13.308C12.4837 15.7929 11.0371 17.8261 10.2092 17.6725V17.6815C9.3813 17.8351 7.93468 15.802 5.50331 13.317C3.29851 11.058 0.875854 9.08809 0.875854 6.00675C0.876654 4.86668 1.2388 3.75848 1.90653 2.85273C2.57427 1.94699 3.5106 1.29391 4.57137 0.994029C5.63215 0.694143 6.75858 0.764074 7.77727 1.19306C8.79596 1.62204 9.65044 2.38629 10.2092 3.36818C10.7658 2.38407 11.6193 1.61727 12.6379 1.18612C13.6566 0.754965 14.7837 0.683436 15.8455 0.982569C16.9072 1.2817 17.8445 1.93487 18.5127 2.84125C19.1809 3.74764 19.5428 4.85686 19.5425 5.99771Z"
                      fill="#CAA8F5"
                    />
                  </svg>
                </button>
              </li>
              <li class="like-share-item" @click="shareVideo">
                <button class="btn-share">
                  <svg fill="none" height="12" viewBox="0 0 16 12" width="16" xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M8.3 9.37499V6.97499C5 6.97499 2.225 7.79999 0.125 11.175C1.385 4.75499 6.1 3.14999 8.3 3.14999V0.674988L15.725 5.02499L8.3 9.37499Z"
                      fill="#FFFFFA"
                    />
                  </svg>
                  Share
                </button>
              </li>
            </ul>
            <div class="equipment-required-box">
              <p class="descriptive-title">
                Equipment Required
              </p>
              <ul class="equipment-required-list">
                <li v-for="equipment in cardDetails?.content?.content_equipment" :key="equipment.id"
                    class="equipment-required-item"
                >
                  {{ equipment.equipment.name }}
                </li>
              </ul>
            </div>
            <div class="description-box">
              <p class="descriptive-title">
                Description
              </p>
              <div class="descriptive-info-box">
                <p class="descriptive-info" v-html="cardDetails?.content?.description" />
              </div>
              <div class="descriptive-list-box presenter-box">
                <ul class="descriptive-list">
                  <li class="descriptive-item">
                    <p class="descriptive-subtitle">
                      Presenter:
                    </p>
                  </li>
                  <li class="descriptive-item">
                    <button class="descriptive-link" @click="navigatePresenterProfile">
                      {{ cardDetails?.content?.presenter?.firstname }} {{ cardDetails?.content?.presenter?.lastname }}
                    </button>
                  </li>
                </ul>
              </div>
              <div class="descriptive-list-box Category-box">
                <ul class="descriptive-list">
                  <li class="descriptive-item">
                    <p class="descriptive-subtitle">
                      Category:
                    </p>
                  </li>
                  <router-link :to="`/on-demand/${cardDetails?.content?.category?.name }`" class="descriptive-item">
                    <button class="descriptive-link">
                      {{ cardDetails?.content?.category?.name }}
                    </button>
                  </router-link>
                </ul>
              </div>
            </div>
          </div>
          <VideoSuggestionSection :related-contents="cardDetails?.relatedContents" />
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import VideoSuggestionSection from "@/components/video-on-demand/video-suggestion-section";
import ContentSharingModal from "@/components/video-on-demand/content-sharing-modal";
import {updateCodCorePoints} from "@/apiManager/gamification";
import {getVideoContentDetail, updateVideoStatus} from "@/apiManager/video-on-demand";
import DefaultImage from "@/assets/images/default_profile_img.png";
import * as toastr from "toastr";
import {VIDEO_STATUS} from "@/common/constants/constants";
import {updateMetaInformation} from "@/utils";

export default {
  name: "VideoOnDemandDetailVue",
  components: {ContentSharingModal, VideoSuggestionSection},
  data() {
    return {
      cardDetails: {},
      iFrameContent: null,
      isShowShareModal: false,
    };
  },
  watch: {
    "$route": {
      immediate: true,
      handler: "fetchVideoContentDetail"
    }
  },
  created() {
    this.fetchVideoContentDetail();
  },
  computed: {
    iframeHtml() {
      return this.cardDetails?.content?.content_source === "CDN"
        ? this.cardDetails?.content?.cdn_metadata.html_frame
        : this.cardDetails?.content?.external_content_meta;
    },
    buttonClass() {
      return this.cardDetails?.content?.is_fvt ? "btn-like active" : "btn-like";
    },
  },
  mounted() {
    window.addEventListener("blur", this.onBlur);
  },

  beforeDestroy() {
    window.removeEventListener("blur", this.onBlur);
  },
  methods: {
    onBlur() {
      if (document.activeElement.tagName === "IFRAME") {
        setTimeout(updateCodCorePoints(), 5000);
      }
    },
    shuffleArray(originalArray) {
      for (let currentIndex = originalArray.length - 1; currentIndex > 0; currentIndex--) {
        const randomIndex = Math.floor(Math.random() * (currentIndex + 1));
        [originalArray[currentIndex], originalArray[randomIndex]] = [originalArray[randomIndex], originalArray[currentIndex]];
      }
      return originalArray;
    },
    async fetchVideoContentDetail() {
      try {
        const response = await getVideoContentDetail(this.$route.params.slug);

        if (response.data?.content?.content_source === "CDN" && response.data.content.cdn_metadata) {
          response.data.content.cdn_metadata = this.modifyIframeHtml(response.data.content.cdn_metadata);
        } else if (response.data.content.external_content_meta) {
          response.data.content.external_content_meta = this.modifyIframeHtml(response.data.content.external_content_meta);
        }

        if (response.data.relatedContents && Array.isArray(response.data.relatedContents)) {
          this.shuffleArray(response.data.relatedContents);
        }

        this.cardDetails = response.data;
        this.updateMetaData();
      } catch (error) {
        toastr.error(error);
      }
    },
    modifyIframeHtml(html_frame) {
      if (!html_frame) {
        toastr.error("Iframe does not exist.");
        return;
      }

      let iframe = document.createElement("div");
      iframe.innerHTML = html_frame;
      let iframeElement = iframe.firstChild;

      if (iframeElement && iframeElement.nodeType === Node.ELEMENT_NODE) {
        ["width", "height", "style"].forEach(attribute => {
          if (iframeElement.hasAttribute(attribute)) {
            iframeElement.removeAttribute(attribute);
          }
        });

        iframeElement.className += " player";
      } else {
        toastr.error("Iframe does not exist or is not an element.");
        return;
      }

      return iframe.innerHTML;
    },

    getImageUrl(imagePath) {
      return imagePath ? this.constants.getImageUrl(imagePath) : DefaultImage;
    },
    shareVideo() {
      this.isShowShareModal = true;
    },
    getChangeVideoStatusPayload() {
      return {
        contentId: this.cardDetails.content.id,
        status: this.cardDetails.content.is_fvt ? VIDEO_STATUS.UNFAVORITE : VIDEO_STATUS.FAVORITE
      };
    },
    navigatePresenterProfile() {
      const {externalPresenter, presenter} = this.cardDetails.content;

      if (externalPresenter) {
        window.location.href = presenter.link;
        return;
      }

      const route = presenter.prviacy === "private" ? "private" : "profile";
      this.$router.push(`/${route}/${presenter.username}`);
    },

    async toggleFavouriteStatus() {
      try {
        await updateVideoStatus(this.getChangeVideoStatusPayload());
        this.cardDetails.content.is_fvt = !this.cardDetails.content.is_fvt;
        let message = this.cardDetails.content.is_fvt
          ? "Video has been added to the favourites."
          : "Video has been removed from the favourites.";

        toastr.success(message);
      } catch (error) {
        const errorMessage = error[0]?.response?.data?.errors[0]?.error;
        if (errorMessage) {
          toastr.error(errorMessage);
        } else {
          toastr.error("An unexpected error occurred.");
        }
      }
    },

    updateMetaData() {
      this.pageTitle = this.cardDetails?.content?.title || "";
      this.pageDesc = this.cardDetails?.content?.description || "";
      const pageUrl = window.location.href;
      updateMetaInformation(this.pageTitle + " | Core Direction", "", this.pageDesc, this.pageTitle + " | Core Direction", "View " + this.pageDesc, "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", pageUrl);
    },
  }
};
</script>

<style>
#vod-standalone {
  padding-top: 32px;
  overflow: hidden;
  margin-bottom: 100px;
}

@media (max-width: 991px) {
  #vod-standalone {
    padding-top: 16px;
  }
}

#vod-standalone .player-box {
  margin-bottom: 32px;
  overflow: hidden;
  border-radius: 16px;
  -webkit-border-radius: 16px;
  -moz-border-radius: 16px;
  -ms-border-radius: 16px;
  -o-border-radius: 16px;
}

@media (max-width: 991px) {
  #vod-standalone .player-box {
    margin-left: -1rem;
    margin-right: -1rem;
    border-radius: 0;
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    -ms-border-radius: 0;
    -o-border-radius: 0;
  }
}

button.btn-like.active {
  background: #690FAD;
}

#vod-standalone .player-box .player {
  display: block;
  width: 100%;
  min-height: 540px;
}

@media (max-width: 767px) {
  #vod-standalone .player-box .player {
    min-height: 200px;
  }

  #vod-standalone .player-box .player .plyr__video-wrapper video {
    width: 100%;
    object-fit: cover;
    object-position: center;
  }
}

#vod-standalone .content-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: start;
  -ms-flex-align: start;
  align-items: flex-start;
  -webkit-column-gap: clamp(30px, 7.291vw, 105px);
  column-gap: clamp(30px, 7.291vw, 105px);
}

@media (max-width: 991px) {
  #vod-standalone .content-box {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }
}

#vod-standalone .content-box .video-content-box {
  width: 100%;
}

@media (min-width: 992px) {
  #vod-standalone .content-box .video-content-box {
    max-width: 820px;
  }
}

#vod-standalone .content-box .video-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 24px;
  font-weight: 600;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 40px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .video-title {
    font-size: 16px;
    line-height: 20px;
    margin-bottom: 24px;
  }
}

#vod-standalone .content-box .like-share-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 16px;
  column-gap: 16px;
  margin-bottom: 32px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .like-share-list {
    margin-bottom: 24px;
  }
}

#vod-standalone .content-box .like-share-list .btn-like {
  width: 40px;
  height: 40px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  border: 1px solid #690FAD;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

#vod-standalone .content-box .like-share-list .btn-share {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 12px;
  column-gap: 12px;
  padding: 10px 16px 10px 20px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  color: #FFFFFF;
  background: #690FAD;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}

#vod-standalone .content-box .equipment-required-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  margin-bottom: 32px;
  -webkit-column-gap: 16px;
  column-gap: 16px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .equipment-required-box {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
    margin-bottom: 4px;
  }
}

#vod-standalone .content-box .equipment-required-box .equipment-required-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 8px;
  column-gap: 8px;
  padding: 12px 0;
  overflow-x: auto;
}

#vod-standalone .content-box .equipment-required-box .equipment-required-list .equipment-required-item {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  padding: 8px 16px;
  background-color: #FDE8E8;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  width: -webkit-max-content;
  width: -moz-max-content;
  width: max-content;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .equipment-required-box .equipment-required-list .equipment-required-item {
    font-size: 12px;
    line-height: 15px;
  }
}

#vod-standalone .content-box .descriptive-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  color: #06070E;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .descriptive-title {
    font-size: 12px;
    line-height: 15px;
  }
}

#vod-standalone .content-box .description-box {
  background: #FFFFFA;
  -webkit-box-shadow: 0px 11px 22px rgba(28, 4, 47, 0.07);
  box-shadow: 0px 11px 22px rgba(28, 4, 47, 0.07);
  border-radius: 16px;
  padding: 24px 24px 16px;
  margin-bottom: 32px;
}

@media (max-width: 767px) {
  #vod-standalone .content-box .description-box {
    padding: 16px 16px 8px;
  }
}

#vod-standalone .content-box .description-box .descriptive-info-box {
  margin-top: 24px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .description-box .descriptive-info-box {
    margin-top: 20px;
  }
}

#vod-standalone .content-box .description-box .descriptive-info-box .descriptive-info {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  color: #06070E;
  margin-bottom: 16px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .description-box .descriptive-info-box .descriptive-info {
    font-size: 12px;
    line-height: 15px;
  }
}

#vod-standalone .content-box .description-box .descriptive-info-box p.descriptive-info:last-child {
  margin-bottom: 8px;
}

#vod-standalone .content-box .description-box .descriptive-list-box {
  padding: 16px 0;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .description-box .descriptive-list-box {
    -webkit-box-align: start;
    -ms-flex-align: start;
    align-items: flex-start;
  }
}

#vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 4px;
  column-gap: 4px;
  row-gap: 12px;
}

#vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  padding: 4px 8px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link {
    font-size: 12px;
    line-height: 15px;
  }
}

#vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link:hover {
  background-color: #CAA8F5;
}

#vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link:active, #vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link:focus, #vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link:focus-visible {
  background-color: #690FAD;
}

#vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link:focus, #vod-standalone .content-box .description-box .descriptive-list-box .descriptive-list .descriptive-link:focus-visible {
  color: #FFFFFA;
}

#vod-standalone .content-box .description-box .descriptive-list-box .descriptive-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
  padding-right: 8px;
}

@media (max-width: 991px) {
  #vod-standalone .content-box .description-box .descriptive-list-box .descriptive-subtitle {
    font-size: 12px;
    line-height: 15px;
    margin-top: 4px;
  }
}

#vod-standalone .content-box .description-box .presenter-box {
  border-bottom: 1px solid rgba(28, 4, 47, 0.1);
}

#vod-standalone .content-box .related-content-box {
  width: 100%;
}

@media (min-width: 992px) {
  #vod-standalone .content-box .related-content-box {
    max-width: 304px;
  }
}

#vod-standalone .content-box .related-content-box .related-content-list {
  margin-top: 16px;
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(296px, 1fr));
  -webkit-column-gap: 20px;
  column-gap: 20px;
  row-gap: 24px;
  padding-left: 4px;
  padding-right: 4px;
}
</style>