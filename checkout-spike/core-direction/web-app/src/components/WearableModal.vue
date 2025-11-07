<template>
  <div id="wearable-modal" class="custom-modal m-auto">
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="setWearableModal">
              <cross-icon />
            </div>
          </div>
          <div class="modal-body px-5">
            <div class="form-container mx-auto">
              <p class="wearable-title app-download-title">
                Download The App
              </p>
              <p class="wearable-subtitle">
                Connect your Device or Wearable
              </p>
              <p class="wearable-desc mx-auto">
                The Core Direction app allows you to collect Steps & Heart Rate points that go towards challenge
                leaderboards and your total Core Points score, as well as much much more!
              </p><br>
              <p class="wearable-desc mx-auto">
                Class & Activity booking is only available on this Website.
              </p>
              <div class="app-link-box mx-auto">
                <div class="app-link-inner-box">
                  <a :href="iosAppLink" class="btn-app-link flex items-center" target="_blank">
                    <div class="app-store-icon-box">
                      <ios-icon />
                    </div>
                    <div class="app-store-content-box">
                      <p class="app-store-subtitle">Download on the</p>
                      <p class="app-store-title">App Store</p>
                    </div>
                  </a>
                  <div class="invitation-link-box">
                    <input ref="iosInputRef" :value="iosAppLink" class="rounded-full invitation-link" readonly
                           type="text"
                    >
                    <button @click="copyToClipboard($refs.iosInputRef)">
                      <CopyIcon />
                    </button>
                  </div>
                </div>
                <div class="app-link-inner-box">
                  <a :href="androidAppLink" class="btn-app-link flex items-center" target="_blank">
                    <div class="app-store-icon-box">
                      <android-icon />
                    </div>
                    <div class="app-store-content-box">
                      <p class="app-store-subtitle">Get in on</p>
                      <p class="app-store-title">Google Play</p>
                    </div>
                  </a>
                  <div class="invitation-link-box">
                    <input ref="androidInputRef" :value="androidAppLink" class="rounded-full invitation-link"
                           readonly type="text"
                    >
                    <button @click="copyToClipboard($refs.androidInputRef)">
                      <CopyIcon />
                    </button>
                  </div>
                </div>
              </div>
              <div class="wearable-btn-box mx-auto flex justify-center flex-col">
                <button class="btn-warable-action btn-learn-more rounded-full" @click="setWearableModal">
                  Close, I only want to book a class
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import CrossIcon from "@/svgs/activity-modal/cross-icon";
import {AndroidIcon, CopyIcon, IosIcon} from "@/svgs/wearable-modal";
import * as toastr from "toastr";
import {mapGetters} from "vuex";

export default {
    name: "WearableModal",
    components: {
        CrossIcon,
        CopyIcon,
        AndroidIcon,
        IosIcon
    },
    props: {
        title: {
            type: String,
            default: "",
        },
        size: {
            type: String,
            default: "md",
        },
    },
    data() {
        return {
            androidAppLink: process.env.VUE_APP_ANDROID_APPLICATION_DOWNLOAD_LINK,
            iosAppLink: process.env.VUE_APP_APPLE_APPLICATION_DOWNLOAD_LINK
        };
    },
    computed: {
        ...mapGetters({
            userProfile: "getStoreUserProfileGetters",
        })
    },
    methods: {
        setWearableModal: function () {
            this.addValueToLocalStorageArray("modalClosedByUser", JSON.stringify(this.userProfile()?.id));
            this.$emit("setWearableModal", false);

        },
        copyToClipboard(ref) {
            ref.select();
            document.execCommand("copy");
            ref.setSelectionRange(0, 0);
            toastr.success("Link copied successfully");
        },
        addValueToLocalStorageArray(key, newValue) {
            let retrievedArray = JSON.parse(localStorage.getItem(key)) || [];
            if (!retrievedArray.includes(newValue)) {
                retrievedArray.push(newValue);
                localStorage.setItem(key, JSON.stringify(retrievedArray));
            }
        }
    },

};
</script>

<style scoped>
#wearable-modal .modal-header {
  padding-top: 13px;
  padding-right: 13px;
  padding-bottom: 0;
}

#wearable-modal .modal-header svg,
#wearable-modal .modal-header path {
  fill: #000000;
}

#wearable-modal .modal-header .btn-prev {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-left: 10px;
}

#wearable-modal .modal-header .btn-prev svg {
  margin-right: 10px;
}

#wearable-modal .modal-outer-box {
  max-width: 650px;
  background: #F1F1F1;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 21px;
}

#wearable-modal .form-container {
  width: 100%;
  max-width: 560px;
}

#wearable-modal .score-point-list {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
  margin-top: 60px;
  margin-bottom: 72px;
}

#wearable-modal .score-point-item.active svg,
#wearable-modal .score-point-item.active path {
  fill: #690FAD;
}

#wearable-modal .wearable-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 30px;
  font-weight: 700;
  line-height: 37px;
  letter-spacing: 0em;
  text-align: center;
  color: #06070E;
  margin-top: 41px;
}

#wearable-modal .wearable-track-list {
  margin-top: 57px;
  margin-bottom: 46px;
  -webkit-column-gap: 30px;
  column-gap: 30px;
  row-gap: 30px;
}

#wearable-modal .wearable-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 24px;
  letter-spacing: -0.011em;
  text-align: center;
  margin-bottom: 15px;
  color: #06070E;
}

#wearable-modal .wearable-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 21px;
  letter-spacing: -0.011em;
  text-align: center;
  color: #06070E;
  max-width: 430px;
}

#wearable-modal .wearable-btn-box {
  width: 100%;
  max-width: 360px;
  row-gap: 10px;
  margin-bottom: 91px;
}

@media (max-width: 767px) {
  #wearable-modal .wearable-btn-box {
    margin-bottom: 75px;
  }
}

#wearable-modal .btn-warable-action {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  padding: 15px;
  border: 1px solid #690FAD;
  color: #690FAD;
}

#wearable-modal .btn-warable-action:hover {
  color: #FFFFFF;
  background: #690FAD;
}

#wearable-modal .btn-warable-action.active {
  color: #FFFFFF;
  background: #690FAD;
}

#wearable-modal .btn-warable-action.active:hover {
  color: #690FAD;
  background: #FFFFFF;
}

#wearable-modal .activity-box {
  row-gap: 9px;
  margin-top: 44px;
  margin-bottom: 61px;
}

#wearable-modal .activity-icon-box {
  margin: auto;
  max-width: 54px;
  max-height: 54px;
}

#wearable-modal .activity-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .activity-points {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .activity-duration {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .point-scoring-box {
  width: 100%;
  max-width: 360px;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 30px;
}

#wearable-modal .point-scoring-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 21px;
  letter-spacing: -0.011em;
  text-align: center;
  margin-bottom: 5px;
  text-transform: uppercase;
}

#wearable-modal .point-scoring-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
  margin-bottom: 18px;
}

#wearable-modal .point-scoring-list {
  -webkit-column-gap: 7.77px;
  column-gap: 7.77px;
  row-gap: 8px;
}

#wearable-modal .wearable-footer {
  margin-top: 73px;
  margin-bottom: 37px;
}

#wearable-modal .btn-skip {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .wearable-pagination-list {
  -webkit-column-gap: 10px;
  column-gap: 10px;
}

#wearable-modal .wearable-pagination-item {
  min-width: 12px;
  max-width: 12px;
  min-height: 12px;
  max-height: 12px;
  background: #DADADA;
}

#wearable-modal .wearable-pagination-item.active {
  background: #690FAD;
}

#wearable-modal .btn-next {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  color: #690FAD;
}

#wearable-modal .btn-next svg {
  margin-left: 10px;
}

#wearable-modal .point-scoring-content-box {
  width: 47px;
}

#wearable-modal .point-scoring-value {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  margin-top: 5px;
}

#wearable-modal .point-scoring-label {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .point-scoring-step-list {
  margin-bottom: 180px;
}

#wearable-modal .btn-connect-wearable-box {
  margin-top: 108px;
}

#wearable-modal .app-link-box {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
  -webkit-column-gap: 30px;
  column-gap: 30px;
  row-gap: 30px;
  max-width: 455px;
  margin-top: 60px;
  margin-bottom: 96px;
}

@media (max-width: 767px) {
  #wearable-modal .app-link-box {
    margin-top: 30px;
    margin-bottom: 48px;
    row-gap: 15px;
  }
}

#wearable-modal .btn-app-link {
  -webkit-column-gap: 20px;
  column-gap: 20px;
  padding: 20px 25px;
  background-color: #690FAD;
  color: #FFFFFA;
  border-radius: 12.8603px;
  margin-bottom: 30px;
  width: 100%;
  max-width: 214px;
  cursor: pointer;
}

@media (max-width: 767px) {
  #wearable-modal .btn-app-link {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 15px;
  }
}

#wearable-modal .btn-app-link .app-store-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 9px;
  font-weight: 500;
  line-height: 11px;
  letter-spacing: -0.011em;
  text-align: left;
  margin-bottom: 3px;
}

#wearable-modal .btn-app-link .app-store-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: -0.011em;
  text-align: left;
}

#wearable-modal .invitation-link-box {
  position: relative;
  width: 100%;
  max-width: 214px;
}

@media (max-width: 767px) {
  #wearable-modal .invitation-link-box {
    margin-left: auto;
    margin-right: auto;
    display: none;
  }
}

#wearable-modal .invitation-link-box input {
  width: 100%;
  max-width: 214px;
  height: 42px;
  background-color: #F1F1F1;
  color: #000000;
  border: 1px solid #690FAD;
  padding: 11px 36px 11px 10px;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px !important;
  font-weight: 400;
  line-height: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

@media screen and (max-width: 991px) {
  #wearable-modal .invitation-link-box input {
    font-size: 12px !important;
    line-height: 14px;
  }
}

#wearable-modal .invitation-link-box button {
  position: absolute;
  right: 17px;
  top: 13px;
}

#wearable-modal .invitation-link-box button svg,
#wearable-modal .invitation-link-box button path {
  fill: #690FAD;
}

#wearable-modal .app-download-title {
  margin-bottom: 60px;
}

@media (max-width: 767px) {
  #wearable-modal .modal-header svg {
    max-width: 28px;
    max-height: 28px;
    margin-left: auto;
  }

  #wearable-modal .modal-body.px-5 {
    padding-left: 15px;
    padding-right: 15px;
  }

  #wearable-modal .wearable-title {
    font-size: 25px;
    line-height: 34px;
    margin-top: 35px;
  }

  #wearable-modal .wearable-track-list {
    margin-top: 45px;
    margin-bottom: 40px;
    -webkit-column-gap: 24px;
    column-gap: 24px;
    row-gap: 24px;
  }

  #wearable-modal .score-point-list {
    -webkit-column-gap: 6px;
    column-gap: 6px;
    row-gap: 6px;
    margin-top: 50px;
    margin-bottom: 62px;
  }

  #wearable-modal .point-scoring-list {
    -webkit-column-gap: 6px;
    column-gap: 6px;
    row-gap: 6px;
  }

  #wearable-modal .point-scoring-step-list {
    margin-bottom: 110px;
  }

  #wearable-modal .btn-connect-wearable-box {
    margin-top: 54px;
  }
}

@media (max-width: 389px) {
  #wearable-modal .modal-header svg {
    max-width: 30px;
    margin-left: auto;
    margin-bottom: auto;
  }

  #wearable-modal .wearable-title {
    font-size: 20px;
    line-height: 27px;
    margin-top: 20px;
  }

  #wearable-modal .wearable-track-list {
    margin-top: 30px;
    margin-bottom: 30px;
    -webkit-column-gap: 5px;
    column-gap: 5px;
    row-gap: 5px;
  }

  #wearable-modal .wearable-track-list svg {
    max-height: 20px;
  }

  #wearable-modal .wearable-subtitle {
    font-size: 14px;
    line-height: 20px;
  }

  #wearable-modal .wearable-desc {
    font-size: 12px;
  }

  #wearable-modal .score-point-list {
    margin-top: 24px;
    margin-bottom: 34px;
    -webkit-column-gap: 5px;
    column-gap: 5px;
    row-gap: 8px;
  }

  #wearable-modal .btn-warable-action {
    line-height: 15px;
    padding: 12px;
  }

  #wearable-modal .wearable-btn-box {
    margin-bottom: 71px;
  }

  #wearable-modal .activity-box {
    margin-top: 34px;
    margin-bottom: 41px;
  }

  #wearable-modal .wearable-footer {
    margin-top: 53px;
  }

  #wearable-modal .point-scoring-step-list {
    margin-bottom: 90px;
  }

  #wearable-modal .btn-connect-wearable-box {
    margin-top: 54px;
  }

  #wearable-modal .app-link-box {
    margin-top: 40px;
    margin-bottom: 50px;
    row-gap: 25px;
  }

  #wearable-modal .btn-app-link {
    margin-bottom: 10px;
  }

  #wearable-modal .btn-app-link .app-store-title {
    font-size: 16px;
  }

  #wearable-modal .invitation-link-box input {
    padding-top: 10px;
    padding-bottom: 10px;
    height: 40px;
  }

  #wearable-modal .app-download-title {
    margin-bottom: 30px;
  }
}
</style>
