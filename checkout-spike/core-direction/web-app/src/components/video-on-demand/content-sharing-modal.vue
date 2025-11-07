<template>
  <div class="wrapper">
    <div id="video-share-modal" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="$parent.isShowShareModal = false">
                <cross-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="vod-content-box mx-auto">
                <p class="modal-title text-center">
                  Its better with friends
                </p>
                <p>Share &amp; inspire others to achieve their inspiring goals.</p>
              </div>
              <div class="form-container mx-auto">
                <ul class="social-icons-list">
                  <ShareNetwork
                    v-for="network in networks"
                    :key="network.name"
                    :network="network.name"
                    :title="network.title"
                    :url="getCurrentUrl"
                  >
                    <li class="social-icon-item">
                      <button class="social-icon-link">
                        <component :is="network.icon" />
                      </button>
                    </li>
                  </ShareNetwork>
                </ul>
                <div class="invitation-link-box">
                  <input id="link_input" ref="link_input" :value="getCurrentUrl" class="rounded-full invitation-link"
                         type="text"
                  >
                  <button class="btn-copy" @click="copyLink">
                    Copy
                  </button>
                </div>
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
import FacebookIcon from "@/svgs/social-icons/facebook-icon.vue";
import LinkedinIcon from "@/svgs/social-icons/linkedin-icon.vue";
import TwitterIcon from "@/svgs/social-icons/twitter-icon.vue";
import EmailIcon from "@/svgs/social-icons/email-icon.vue";

export default {
    name: "ContentSharingModal",
    components: {
        EmailIcon,
        TwitterIcon,
        LinkedinIcon,
        FacebookIcon,
        CrossIcon
    },
    data() {
        return {
            networks: [
                {name: "facebook", title: "Coredirection", icon: FacebookIcon},
                {name: "linkedin", title: "Coredirection", icon: LinkedinIcon},
                {name: "twitter", title: "Coredirection", icon: TwitterIcon},
                {name: "email", title: "Coredirection", icon: EmailIcon},
            ],
        };

    },

    methods: {
        copyLink() {
            let copyText = document.getElementById("link_input");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
        },
    },
    computed: {
        getCurrentUrl() {
            return window.location.href;
        }
    }

};
</script>

<style scoped>
#video-share-modal .modal-outer-box {
  position: relative;
  background: #FFFFFF;
  max-width: 320px;
}

@media (min-width: 992px) {
  #video-share-modal .modal-outer-box {
    max-width: 802px;
  }
}

#video-share-modal .modal-header {
  position: absolute;
  right: 16px;
  padding: 0;
  top: 16px;
}

#video-share-modal .modal-body {
  padding: 64px 32px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-column-gap: 64px;
  column-gap: 64px;
  row-gap: 32px;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
}

@media (min-width: 992px) {
  #video-share-modal .modal-body {
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    padding: 72px 64px;
  }
}

#video-share-modal .vod-content-box {
  width: 100%;
  max-width: 290px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  row-gap: 32px;
  padding: 8px;
}

@media (min-width: 769px) {
  #video-share-modal .vod-content-box {
    row-gap: 43px;
  }
}

#video-share-modal .form-container {
  width: 100%;
  max-width: 320px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  row-gap: 32px;
}

#video-share-modal .modal-title {
  font-size: 24px;
  line-height: 29px;
  font-weight: 700;
  text-align: left;
  color: #06070E;
}

@media screen and (max-width: 991px) {
  #video-share-modal .modal-title {
    font-size: 20px;
    line-height: 24px;
  }
}

#video-share-modal p {
  color: #06070E;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: left;
}

@media screen and (max-width: 991px) {
  #video-share-modal p {
    font-size: 12px;
    line-height: 15px;
  }
}

#video-share-modal .search-add-friend {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: space-evenly;
  -ms-flex-pack: space-evenly;
  justify-content: space-evenly;
  margin-bottom: 39px;
}

#video-share-modal .social-icons-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  margin-bottom: 38px;
  width: 100%;
  max-width: 317px;
  margin-left: auto;
  margin-right: auto;
}

@media screen and (max-width: 991px) {
  #video-share-modal .social-icons-list {
    margin-bottom: 31px;
  }
}

#video-share-modal .social-icons-list .social-icon-item .social-icon-link {
  width: 56px;
  height: 56px;
  background-color: #690FAD;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  border-radius: 50px;
}

@media screen and (max-width: 991px) {
  #video-share-modal .social-icons-list .social-icon-item .social-icon-link {
    width: 48px;
    height: 48px;
  }
}

@media screen and (max-width: 991px) {
  #video-share-modal .social-icons-list .social-icon-item .social-icon-link svg,
  #video-share-modal .social-icons-list .social-icon-item .social-icon-link img {
    max-width: 26px;
  }
}

#video-share-modal .invitation-link-box {
  position: relative;
  margin-left: auto;
  margin-right: auto;
  width: 100%;
  max-width: 320px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  -ms-flex-direction: row;
  flex-direction: row;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 4px 8px;
  gap: 8px;
  background-color: #F1F1F1;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}

#video-share-modal .invitation-link-box input {
  width: 100%;
  background-color: transparent;
  color: #06070E;
  padding: 11px 8px;
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

@media screen and (max-width: 991px) {
  #video-share-modal .invitation-link-box input {
    font-size: 12px;
    line-height: 14px;
  }
}

#video-share-modal .invitation-link-box .btn-copy {
  padding: 8px 16px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  color: #FFFFFA;
  background-color: #690FAD;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
}
</style>