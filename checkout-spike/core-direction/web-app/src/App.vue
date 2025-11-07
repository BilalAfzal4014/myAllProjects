<template>
  <div id="app">
    <div v-if="isWindowScreenLoaded" class="before_ajax_loading">
      <preloader />
    </div>
    <router-view />
    <WearableModal v-if="showWearableModal" @setWearableModal="setWearableModal" />
    <SubscriptionCorePremiumModal v-if="$store.state.corePremiumModal" />
    <Popup>
      <ModalRedeem v-if="$store.state.modalRedeem" />
      <ModalPincode v-else-if="$store.state.modalRedeemRequiredCode" />
      <ModalCongrats v-else-if="$store.state.modalRedeemCongrats" />

    </Popup>
  </div>
</template>

<script>
import axios from "axios";
import WearableModal from "./components/WearableModal.vue";
import ModalPincode from "@/components/BusinessProfile/DealsAndOffers/ModalPincode.vue";
import ModalRedeem from "@/components/BusinessProfile/DealsAndOffers/ModalRedeem.vue";
import ModalCongrats from "@/components/BusinessProfile/DealsAndOffers/ModalCongrats.vue";
import Popup from "@/components/UIElements/Popup.vue";
import SubscriptionCorePremiumModal from "@/components/CorePremiumSubscription/SubscriptionCorePremiumModal";
import {mapGetters} from "vuex";
import Preloader from "@/svgs/preloader/preloader";

export default {
  name: "App",
  data() {
    return {
      isWindowScreenLoaded: true,
    };
  },
  components: {
    ModalRedeem,
    Preloader,
    WearableModal,
    ModalCongrats,
    ModalPincode,
    SubscriptionCorePremiumModal, 
    Popup
  },
  computed: {
    ...mapGetters({
      userProfile: "getStoreUserProfileGetters",
      showWearableModal: "getWearableModalValue"
    }),
  },
  created() {
    axios.interceptors.request.use((config) => {
      const {url} = config;
      if (this.constants.getUrl("getCompany") === url) return config;
      this.isWindowScreenLoaded = true;
      return config;
    }, (error) => {
      this.isWindowScreenLoaded = false;
      return Promise.reject(error);
    });

    axios.interceptors.response.use((response) => {
      this.isWindowScreenLoaded = false;
      return response;
    }, (error) => {
      this.isWindowScreenLoaded = false;
      return Promise.reject(error);
    });
  },
  methods: {
    hideGlobalLoader() {
      this.isWindowScreenLoaded = false;
    },
    setWearableModal(value) {
      this.$store.commit("setShowWearableModal", value);
    }
  },
  mounted() {
    window.addEventListener("load", this.hideGlobalLoader);

  },


};
</script>

<style lang="scss">
@import "@/assets/scss/app";
.ajax_loader {
  display: none;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  min-height: 100vh;
  height: 100%;
  width: 100%;
  background: rgba(255, 255, 255, 0.8);
  z-index: 999999999999;
}

.before_ajax_loading {
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  min-height: 100vh;
  height: 100%;
  width: 100%;
  background: rgba(255, 255, 255, 0.8);
  z-index: 999999999999;
}

.ajax_loader svg, .before_ajax_loading svg {
  position: absolute;
  top: 50%;
  left: 50%;
  width: 110px;
  transform: translate(-60%, -70%);
}

@-webkit-keyframes animate-svg-stroke-1 {
  0% {
    stroke-dashoffset: 290.93853759765625px;
    stroke-dasharray: 290.93853759765625px;
  }

  100% {
    stroke-dashoffset: 581.8770751953125px;
    stroke-dasharray: 290.93853759765625px;
  }
}

@keyframes animate-svg-stroke-1 {
  0% {
    stroke-dashoffset: 290.93853759765625px;
    stroke-dasharray: 290.93853759765625px;
  }

  100% {
    stroke-dashoffset: 581.8770751953125px;
    stroke-dasharray: 290.93853759765625px;
  }
}

@-webkit-keyframes animate-svg-fill-1 {
  0% {
    fill: transparent;
  }

  100% {
    fill: rgb(105, 15, 173);
  }
}

@keyframes animate-svg-fill-1 {
  0% {
    fill: transparent;
  }

  100% {
    fill: rgb(105, 15, 173);
  }
}

.svg-elem-1 {
  -webkit-animation: animate-svg-stroke-1 1.2s cubic-bezier(0.47, 0, 0.745, 0.715) 0s both,
  animate-svg-fill-1 0.6s cubic-bezier(0.47, 0, 0.745, 0.715) 1.1s both;
  animation: animate-svg-stroke-1 1.2s cubic-bezier(0.47, 0, 0.745, 0.715) 0s both,
  animate-svg-fill-1 0.6s cubic-bezier(0.47, 0, 0.745, 0.715) 1.1s both;
}

@-webkit-keyframes animate-svg-stroke-2 {
  0% {
    stroke-dashoffset: 275.6835632324219px;
    stroke-dasharray: 275.6835632324219px;
  }

  100% {
    stroke-dashoffset: 551.3671264648438px;
    stroke-dasharray: 275.6835632324219px;
  }
}

@keyframes animate-svg-stroke-2 {
  0% {
    stroke-dashoffset: 275.6835632324219px;
    stroke-dasharray: 275.6835632324219px;
  }

  100% {
    stroke-dashoffset: 551.3671264648438px;
    stroke-dasharray: 275.6835632324219px;
  }
}

@-webkit-keyframes animate-svg-fill-2 {
  0% {
    fill: transparent;
  }

  100% {
    fill: rgb(105, 15, 173);
  }
}

@keyframes animate-svg-fill-2 {
  0% {
    fill: transparent;
  }

  100% {
    fill: rgb(105, 15, 173);
  }
}

.svg-elem-2 {
  -webkit-animation: animate-svg-stroke-2 1.2s cubic-bezier(0.47, 0, 0.745, 0.715) 0.12s both,
  animate-svg-fill-2 0.6s cubic-bezier(0.47, 0, 0.745, 0.715) 1.2000000000000002s both;
  animation: animate-svg-stroke-2 1.2s cubic-bezier(0.47, 0, 0.745, 0.715) 0.12s both,
  animate-svg-fill-2 0.6s cubic-bezier(0.47, 0, 0.745, 0.715) 1.2000000000000002s both;
}

@-webkit-keyframes animate-svg-stroke-3 {
  0% {
    stroke-dashoffset: 273.7530517578125px;
    stroke-dasharray: 273.7530517578125px;
  }

  100% {
    stroke-dashoffset: 547.506103515625px;
    stroke-dasharray: 273.7530517578125px;
  }
}

@keyframes animate-svg-stroke-3 {
  0% {
    stroke-dashoffset: 273.7530517578125px;
    stroke-dasharray: 273.7530517578125px;
  }

  100% {
    stroke-dashoffset: 547.506103515625px;
    stroke-dasharray: 273.7530517578125px;
  }
}

@-webkit-keyframes animate-svg-fill-3 {
  0% {
    fill: transparent;
  }

  100% {
    fill: rgb(105, 15, 173);
  }
}

@keyframes animate-svg-fill-3 {
  0% {
    fill: transparent;
  }

  100% {
    fill: rgb(105, 15, 173);
  }
}

.svg-elem-3 {
  -webkit-animation: animate-svg-stroke-3 1.2s cubic-bezier(0.47, 0, 0.745, 0.715) 0.24s both,
  animate-svg-fill-3 0.6s cubic-bezier(0.47, 0, 0.745, 0.715) 1.3s both;
  animation: animate-svg-stroke-3 1.2s cubic-bezier(0.47, 0, 0.745, 0.715) 0.24s both,
  animate-svg-fill-3 0.6s cubic-bezier(0.47, 0, 0.745, 0.715) 1.3s both;
}
</style>
