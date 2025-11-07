<template>
  <div class="demo">
    <swiper v-if="sliderData.length > 0" :options="swiperOption" class="swiper">
      <swiper-slide v-for="(offer,index) in sliderData" :key="index">
        <OffersCard :availableAt="offer.available_at ?? null"
                    :discountPercentage="offer.discount_percentage ?? null" :isBgWhite="false"
                    :isCode="offer.is_redemption_code_required" :isUnlimited="!!offer.is_unlimited_redemptions"
                    :offerDescription="offer.description ?? ``" :offerId="offer.id ?? null"
                    :offerName="offer.name ?? ``" :offerProvider="offer?.Facility?.company_name"
                    :offerRenewal="offer.offer_renew_date ?? ``"
                    :offerType="offer.offer_type ?? ``"
                    :termsAndConditions="offer.terms_conditions ?? ``"
                    :totalRedemptions="offer.allowed_number_of_redemption ?? null"
                    :userRedemptions="offer.user_redemptions ?? null"
        />
      </swiper-slide>
      <swiper-slide>
        <div class="btn-package-detail-wrap">
          <button class="btn-browse-more" @click="$emit('navigateToTab', 'dealsAndOffers')">
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
import OffersCard from "./OffersCard.vue";

export default {
  props: {
    sliderData: {
      type: Array,
      default: () => [],
    },
    offerName: {
      type: String,
      default: "",
    },
    offerId: {
      type: Number,
      default: null,
    },
    offerType: {
      type: String,
      default: "",
    },
    totalRedemptions: {
      type: Number,
      default: null,
    },
    userRedemptions: {
      type: Number,
      default: null,
    },
    discountPercentage: {
      type: Number,
      default: null,
    },
    availableAt: {
      type: String,
      default: "",
    },
    offerDescription: {
      type: String,
      default: "",
    },
    termsAndConditions: {
      type: String,
      default: "",
    },
    offerRenewal: {
      type: String,
      default: "",
    },
    isCode: {
      type: Boolean,
      default: false,
    },
    offerProvider: {
      type: String,
      default: "",
    }
  },
  components: {
    Swiper,
    SwiperSlide,
    OffersCard,
  },
  data() {
    return {
      swiperOption: {
        slidesPerView: 4,
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
            slidesPerView: 1.3,
            spaceBetween: 20
          }
        }
      },
    };
  },

};
</script>

<style lang="scss" scoped>
.swiper-slide {
  padding: 16px 0 32px;
  width: 400px !important;
  @media (max-width: 480px) {
    width: 314px !important;
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
    min-height: 180px;
  }
}

.btn-package-detail-wrap {
  background-color: #FFFFFA;
}

.swiper-pagination {
  display: none;
}
</style>