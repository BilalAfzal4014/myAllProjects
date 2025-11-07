<template>
  <div>
    <package-modal v-if="modal.isModalOpen" :pack="modal.packageDetails" @closeModal="closeModal"
                   @hidePackageModal="modal.isModalOpen = false"
    />
    <swiper v-if="sliderData.length > 0" :options="swiperPackages" class="swiper">
      <swiper-slide v-for="(pack,index) in sliderData" :key="index">
        <PackageCard :pack="pack" :packageDiscountedPrice="pack.discounted_price ?? ''"
                     :packageName="pack.name ?? ''" :packagePrice="pack.price ?? ''"
                     :packageValidityDays="pack.validity_days ?? null"
                     :packageVisits="pack.visits ?? null"
                     @sendPackageDescription="getPackageDescription"
        />
      </swiper-slide>
      <swiper-slide>
        <div class="btn-package-detail-wrap">
          <button class="btn-browse-more" @click="$emit('navigateToTab', 'packages')">
            <span> Browse more </span>
            <img alt="browse more" src="@/assets/images/browse-more-icon.svg">
          </button>
        </div>
      </swiper-slide>

      <div slot="pagination" class="swiper-pagination" />
    </swiper>
  </div>
</template>
<script>
import {Swiper, SwiperSlide} from "vue-awesome-swiper";
import "swiper/css/swiper.css";
import PackageCard from "./PackageCard.vue";
import PackageModal from "@/partials/modal/package-modal";

export default {
  name: "SliderPackages",
  components: {
    PackageModal,
    Swiper,
    SwiperSlide,
    PackageCard,
  },
  props: {
    sliderData: {
      type: Array,
      default: () => [],
    }
  },
  data() {
    return {
      modal: {
        isModalOpen: false,
        packageDetails: {},
      },
      packagesList: [],
      swiperPackages: {
        slidesPerView: 3,
        spaceBetween: 20,
        pagination: {
          el: ".swiper-pagination",
          clickable: true
        },
        breakpoints: {
          1200: {
            slidesPerView: 3,
            spaceBetween: 20
          },
          1024: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          640: {
            slidesPerView: 2,
            spaceBetween: 20
          },
          320: {
            slidesPerView: 1.35,
            spaceBetween: 20
          }
        }
      }
    };
  },
  methods: {
    closeModal() {
      this.modal.isModalOpen = false;
    },
    openModal() {
      this.modal.isModalOpen = true;
    },
    getPackageDescription(details) {
      this.modal.packageDetails = details;
      this.openModal();
    }
  }
};
</script>

<style lang="scss" scoped>
.swiper-slide {
  padding: 16px 0 32px;
  width: 400px !important;
  @media (max-width: 480px) {
    width: 312px !important;
    padding: 16px 0 20px;
  }
}

.swiper-container {
  overflow: inherit;
  margin-right: -100px;
  width: calc(100% + 100px);
}

.btn-browse-more {
  display: flex;
  align-items: center;
  justify-content: center;
  column-gap: 8px;
  width: 176px;
  height: 100%;
  min-height: 218px;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 600;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  padding: 10px;
  text-transform: capitalize;
  border-radius: 16px;
  box-shadow: 0px 12px 16px 0px rgba(28, 4, 47, 0.08), 0px 4px 6px 0px rgba(28, 4, 47, 0.03);
  background: linear-gradient(270.17deg, #7812C6 15.31%, rgba(93, 13, 153, 0.65) 99.74%);
  border: 0.5px solid #7812c6;
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  @media (max-width: 480px) {
    min-height: 170px;
  }
}

.btn-package-detail-wrap {
  background-color: #FFFFFA;
}

.swiper-pagination {
  display: none;
}
</style>