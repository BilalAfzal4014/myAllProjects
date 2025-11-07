<template>
  <div class="deals-card-box">
    <div :class="`package-card ${isBgWhite ? '' : 'bg-not-white'}`">
      <div class="package-header">
        <p class="package-name">
          {{ offerName }}
        </p>
        <div class="package-header-info-box">
          <p v-if="offerType !== `Buy 1 Get 1 Free`" class="package-current-price">
            {{ discountPercentage ? `${discountPercentage}% Off` : "100% Off" }}
          </p>
          <p class="package-prev-price">
            {{ offerType }}
          </p>
        </div>
      </div>

      <button class="btn-package-detail" @click="redeemOffer">
        <InfoIconCurl />
        View Offer Details
      </button>
      <div class="package-footer">
        <div class="package-footer-info-box">
          <p id="show-modal" class="class-pass">
            No. of Redemption(s)
          </p>
          <p v-if="isUnlimited" class="package-validity">
            Unlimited
          </p>

          <p v-else class="package-validity">
            {{ totalRedemptions ? `${userRedemptions ?? 0} / ${totalRedemptions}` : "" }}
          </p>
        </div>
        <Button v-if="!isUnlimited && userRedemptions < totalRedemptions" :btnType="BUTTON_TYPES.PRIMARY"
                :isDisabled="false" @click.native="redeemOffer"
        >
          Redeem
        </Button>
        <Button v-else-if="!isUnlimited && userRedemptions >= totalRedemptions" :btnType="BUTTON_TYPES.PRIMARY"
                :isDisabled="true"
        >
          Redeem
        </Button>
        <Button v-else :btnType="BUTTON_TYPES.PRIMARY" :isDisabled="false" @click.native="redeemOffer">
          Redeem
        </Button>
      </div>
    </div>
  </div>
</template>

<script>
import {Button, BUTTON_TYPES} from "@/components/Buttons";
import InfoIconCurl from "@/svgs/InfoIconCurl.vue";

export default {
  name: "OffersCard",
  components: {
    Button,
    InfoIconCurl
  },
  props: {
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
    },
    isUnlimited: {
      type: Boolean,
      default: false,
    },
    isBgWhite: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      BUTTON_TYPES,
    };
  },
  methods: {
    redeemOffer() {
      const offerData = {
        offerName: this.offerName,
        offerId: this.offerId,
        offerType: this.offerType,
        totalRedemptions: this.totalRedemptions,
        userRedemptions: this.userRedemptions,
        discountPercentage: this.discountPercentage,
        availableAt: this.availableAt,
        offerDescription: this.offerDescription,
        termsAndConditions: this.termsAndConditions,
        offerRenewal: this.offerRenewal,
        isCode: this.isCode,
        offerProvider: this.offerProvider,
        isUnlimited: this.isUnlimited
      };
      return this.$store.commit("setIsModalRedeem", offerData);
    },
  }

};
</script>

<style lang="scss" scoped>
.deals-card-box {
  .package-card {
    display: -ms-flexbox;
    display: flex;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    row-gap: 16px;
    background-color: $white;
    padding: 32px;
    @include depth-sm;
    border-radius: 8px;
    @media (max-width: 767px) {
      padding: 20px;
      row-gap: 8px;
    }

    &.bg-not-white {
      background: #FFFFFA;
    }

    .package-header {
      display: grid;
      grid-template-columns: 5fr 3fr;
      -webkit-column-gap: 10px;
      column-gap: 10px;

      .package-name {
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
        font-style: normal;
        font-weight: 600;
        line-height: 17px;
        letter-spacing: 0em;
        text-align: left;
        margin-bottom: 0;
        text-transform: uppercase;
        color: #06070E;
        width: 100%;
        max-width: 232px;
        height: 51px;
      }

      .package-header-info-box {
        .package-current-price {
          font-family: 'Montserrat', sans-serif;
          font-size: 14px;
          font-style: normal;
          font-weight: 700;
          line-height: 17px;
          letter-spacing: 0em;
          text-align: right;
        }

        .package-prev-price {
          font-family: 'Montserrat', sans-serif;
          font-size: 14px;
          font-style: normal;
          font-weight: 600;
          line-height: 17px;
          letter-spacing: 0em;
          text-align: right;
          margin-top: 4px;
          margin-bottom: 0;
          color: #06070E;
          text-decoration: none;
          text-transform: capitalize;
        }
      }
    }

    .btn-package-detail {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      font-family: 'Montserrat', sans-serif;
      font-size: 12px;
      font-style: normal;
      font-weight: 400;
      line-height: 15px;
      letter-spacing: 0em;
      text-align: left;
      text-decoration: underline;
      padding: 6px 4px;
      color: #06070E;
      width: -webkit-max-content;
      width: -moz-max-content;
      width: max-content;
      margin: 0;

      svg {
        margin-right: 8px;
      }
    }

    .package-footer {
      display: grid;
      grid-template-columns: 5fr 2fr;
      -webkit-column-gap: 10px;
      column-gap: 10px;

      .package-footer-info-box {
        .class-pass, .package-validity {
          font-family: 'Montserrat', sans-serif;
          font-size: 14px;
          font-style: normal;
          font-weight: 600;
          line-height: 17px;
          letter-spacing: 0em;
          text-align: left;
          margin-top: 3px;
          margin-bottom: 4px;
          color: #06070E;
        }

        .package-validity {
          font-weight: 400;
          margin-bottom: 0;
        }

        .package-validity-total-redeem {
          font-weight: 600;
        }
      }
    }
  }
}
</style>