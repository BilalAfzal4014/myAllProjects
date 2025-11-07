<template>
  <swiper :options="partnersOption" class="swiper partner-logo-slider">
    <swiper-slide v-for="partner in partners" :key="partner.name">
      <div class="partner-logo">
        <img :alt="partner.name" :src="partner.imageUrl" loading="lazy">
      </div>
    </swiper-slide>
  </swiper>
</template>

<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import MeerasLogo from "@/assets/images/partner-logo/meeras.svg";
import NakheelLogo from "@/assets/images/partner-logo/nakheel.svg";
import DmpLogo from "@/assets/images/partner-logo/dmp.svg";
import DscLogo from "@/assets/images/partner-logo/dsc.svg";
import ExpoLogo from "@/assets/images/partner-logo/expo.svg";
import JHRLogo from "@/assets/images/partner-logo/jhr.svg";
import EmaarLogo from "@/assets/images/partner-logo/emaar.svg";
import DMCLogo from "@/assets/images/partner-logo/dmc.svg";
import HQWSLogo from "@/assets/images/partner-logo/hqws.svg";
import MAFLogo from "@/assets/images/partner-logo/maf.svg";
import DHLogo from "@/assets/images/partner-logo/dh.svg";

export default {
  name: "SubscriptionPartnersSlider",
  components: {
    Swiper,
    SwiperSlide
  },
  data() {
    return {
      partners: [
        {"name": "Meeras", "imageUrl": MeerasLogo},
        {"name": "Nakheel", "imageUrl": NakheelLogo},
        {"name": "DMP", "imageUrl": DmpLogo},
        {"name": "DSC", "imageUrl": DscLogo},
        {"name": "Expo", "imageUrl": ExpoLogo},
        {"name": "JHR", "imageUrl": JHRLogo},
        {"name": "Emaar", "imageUrl": EmaarLogo},
        {"name": "DMC", "imageUrl": DMCLogo},
        {"name": "HQWS", "imageUrl": HQWSLogo},
        {"name": "MAF", "imageUrl": MAFLogo},
        {"name": "DH", "imageUrl": DHLogo}
      ],
      partnersOption: {
        speed: 3000,
        loop: true,
        autoplay: {
          delay: 0,
          disableOnInteraction: false,
        },
        slidesPerView: "auto",
        spaceBetween: 50,
        allowTouchMove: true,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev"
        },
      }
    };
  },
  methods: {
    getImage(imagePath) {
      return require(imagePath);
    }
  }
};
</script>

<style lang="scss" scoped>
.swiper-wrapper {
  column-gap: 36px;
  margin: 5px 0;
  @media (max-width: 767px) {
    column-gap: 27px;
  }

  .partner-logo {
    width: 165.594px;
    height: 72.447px;
    @media (max-width: 767px) {
      width: 124.195px;
      height: 54.335px;
    }

    img, svg {
      width: 100%;
      object-fit: contain;
      object-position: center;
    }
  }
}

</style>