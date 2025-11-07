<template>
  <div
    id="social_sharing_modal"
    :class="`wrapper ${showModal ? '' : 'hidden'}`"
  >
    <div id="inspire-your-network-modal" class="custom-modal m-auto">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeModal">
                <cross-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <h3 class="modal-title text-center mt-10">
                Share with friends
              </h3>
              <div class="form-container mx-auto">
                <p>
                  Use the below sharing options to get your friends to join.
                </p>
                <ul class="social-icons-list">
                  <Facebook :link="urlToShow ?? ''" />
                  <Linkedin :link="urlToShow ?? ''" />
                  <Twitter :link="urlToShow ?? ''" />
                  <Email :link="urlToShow ?? ''" />
                </ul>
              </div>
              <div class="invitation-link-box">
                <input
                  id="link_input"
                  :value="urlToShow"
                  class="rounded-full invitation-link"
                  type="text"
                >
                <button type="button" @click="copyLink">
                  <clipboard-icon />
                </button>
              </div>
              <div class="btn-close-modal-inspire-your-network">
                <button class="btn-modal-close bg-gradient" @click="closeModal">
                  No, Thanks
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
import Facebook from "../partials/SocialSharingOnSignup/Facebook";
import Linkedin from "../partials/SocialSharingOnSignup/Linkedin";
import Twitter from "../partials/SocialSharingOnSignup/Twitter";
import Email from "../partials/SocialSharingOnSignup/Email";
import Emitter from "tiny-emitter/instance";
import CrossIcon from "@/svgs/activity-modal/cross-icon";
import ClipboardIcon from "@/svgs/clipboard-icon";
import {hideBodyScroll, showBodyScroll} from "@/utils";

export default {
  name: "SocialSharingOnSignup",
  components: {ClipboardIcon, CrossIcon, Email, Twitter, Linkedin, Facebook},
  data() {
    return {
      shareUrl: "",
      eventTriggered: false,
    };
  },
  props: {
    showModal: {
      type: Boolean,
      default: false,
    },
    userDetail: {
      type: Object,
      default: null
    },
    slugUrl: {
      type: String,
      default: "",
    },
  },
  computed: {
    urlToShow() {
      return this.eventTriggered ? this.shareUrl : this.getLatestUrl;
    },
    getLatestUrl() {
      if (this.userDetail?.privacy === "private" && this.slugUrl.includes("/private/")) {
        return this.getHostName() + this.slugUrl;
      } else if (this.userDetail?.privacy === "public" && this.slugUrl.includes("/profile/")) {
        return this.getHostName() + this.slugUrl;
      } else if (this.$route.path === "/community" && this.$route.query?.dairy_shared && this.$route.query?.slug) {
        return this.getHostName() + `/community?dairy_shared=${this.$route.query?.dairy_shared}&slug=${this.slugUrl}`;
      } else if (this.$route.path === "/community") {
        return this.getHostName() + "/community?dairy_shared=true&slug=" + this.slugUrl;
      } else if (this.$route.path === "/booking") {
        return this.getHostName() + "/booking/" + this.slugUrl;
      } else {
        return this.getHostName();
      }
    }
  },
  methods: {
    setShowActivityModal: function () {
      this.$emit("setShowActivityModal", false);
    },
    copyLink() {
      let copyText = document.getElementById("link_input");
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      navigator.clipboard.writeText(copyText.value);
    },
    closeModal() {
      this.setShowActivityModal();
      this.$emit("onCloseSharingModal");
      showBodyScroll();
      if (this.$route.name === "SignupComponent") {
        window.location.href = "/our-services";
      }
    },
    getBaseUrl() {
      this.shareUrl = window.location.origin;
    },
    getHostName() {
      return window.location.origin;
    },
    getSharingUrl(type = "default", slug = "") {
      return {
        "booking": `${this.getHostName()}/booking/${slug}`,
        "shareActivity": `${this.getHostName()}/community?dairy_shared=true&slug=${slug}`,
        "paidBooking": this.getCompleteUrl(),
        "default": `${this.getHostName()}`
      }[type];
    },
    getCompleteUrl() {
      return window.location.href;
    },
    initShareUrl() {
      if (this.slugUrl.includes("profile") || this.slugUrl.includes("private")) {
        this.shareUrl = this.getHostName() + this.slugUrl;
      } else {
        this.shareUrl = this.getHostName() + "/community?dairy_shared=true&slug=" + this.slugUrl;
      }
    },

    updateShareUrl() {
      this.shareUrl = this.shareUrl + "/community?dairy_shared=true&slug=" + this.slugUrl;
    },

    registerEmitterEvents() {
      Emitter.on("social_sharing_modal", (type, slug) => {
        this.eventTriggered = true;
        if (type) {
          this.updateShareUrlBasedOnSharingModal(type, slug);
        } else {
          this.initShareUrl();
        }
        hideBodyScroll();
      });
    },

    updateShareUrlBasedOnSharingModal(type, slug) {
      this.shareUrl = this.getSharingUrl(type, slug);
    }

  },

  created() {
    this.registerEmitterEvents();
  },
};
</script>

<style scoped>
.btn-modal-close {
  font-family: "Montserrat", sans-serif;
  width: 36px;
  cursor: pointer;
}
</style>
