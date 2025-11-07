<template>
  <section id="core-premium-subscription">
    <div class="custom-container">
      <div class="row">
        <div class="content-box">
          <p class="title">
            Subscribe to Core Premium <br> Join Challenges & Win Prizes
          </p>
          <SubscriptionBenefitsList />
          <div class="notice-box">
            <p class="desc">
              On registration your payment will auto-renew monthly at AED {{ packageData?.price ?? 0 }}.
            </p>
            <p class="desc">
              You can cancel any time during your subscription. Cancellation of subscription will result in expiry of
              all Deals & Offers.
            </p>
          </div>
        </div>
        <SubscriptionUnauthorized v-if="!authUser" />
        <div v-if="authUser && isLoading" class="payment-box">
          Loading data, please wait...
        </div>
        <div v-else>
          <div v-if="authUser && !userPremiumDetails.isPremiumPurchased" class="payment-box">
            <SubscriptionByCreditCard :packageData="packageData" @successfullyPremium="successfullyPremium($event)" />
          </div>
          <SusbscriptionSuccessfully v-if="authUser && userPremiumDetails.isPremiumPurchased"
                                     :cardLastDigits="`${userPremiumDetails.details.last4}`"
                                     :endDate="formattedDate(userPremiumDetails.details.end_date)"
                                     :startDate="formattedDate(userPremiumDetails.details.start_date)"
                                     paidAmount="36.75"
          />
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import SubscriptionUnauthorized from "./SubscriptionUnauthorized.vue";
import SusbscriptionSuccessfully from "./SusbscriptionSuccessfully.vue";
import SubscriptionBenefitsList from "./SubscriptionBenefitsList.vue";
import SubscriptionByCreditCard from "./SubscriptionByCreditCard.vue";
import {updateMetaInformation} from "@/utils";
import {getPremiumUserDetails} from "@/apiManager/core-premium";

export default {
  name: "SubscriptionInfo",
  components: {
    SubscriptionBenefitsList,
    SubscriptionByCreditCard,
    SusbscriptionSuccessfully,
    SubscriptionUnauthorized
  },
  props: {
    packageData: {
      type: Object,
      default: () => {
      },
    }
  },
  data() {
    return {
      premiumData: {},
      userPremiumDetails: {},
      isLoading: true,
    };
  },
  mounted() {
    updateMetaInformation("Core Premium | Core Direction", "", "Sign up to Core Premium & unlock Deals & Offers from the UAE's top wellness brands", "Core Premium | Core Direction", "Sign up to Core Premium & unlock Deals & Offers from the UAE's top wellness brands");
    this.checkUserPremiumData();
  },
  computed: {
    authUser() {
      return this.$store.state.token;
    }
  },
  methods: {
    formattedDate(originalDate) {
      const dateObj = new Date(originalDate);
      const options = {year: "numeric", month: "2-digit", day: "2-digit"};
      let formatted = dateObj.toLocaleDateString("en-US", options);
      formatted = formatted.replace(/\//g, "-");
      return formatted;
    },
    async checkUserPremiumData() {
      try {
        const response = await getPremiumUserDetails();
        this.userPremiumDetails = response;
        this.isLoading = false;
      } catch (e) {
        return e.message;
      }
    },
    successfullyPremium(e) {
      this.premiumData = e;
      this.userPremiumDetails = {
        "isPremiumPurchased": true,
        "details": {
          "start_date": e.startDate,
          "end_date": e.endDate,
          "last4": e.network
        }
      };
    }
  },
};
</script>

<style lang="scss" scoped>
#custom-button {
  height: 30px;
  outline: 1px solid grey;
  background-color: green;
  padding: 5px;
  color: white;
}

#card-error {
  color: red;
}


#core-premium-subscription {
  position: relative;
  padding: 64px 20px;
  @media (max-width: 767px) {
    padding: 32px 20px 48px;
  }

  //&::before,
  //&::after {
  //  position: absolute;
  //  content: "";
  //  width: 686px;
  //  height: 686px;
  //  border-radius: 686px;
  //  background: rgba(105, 15, 173, 0.30);
  //  filter: blur(500px);
  //  z-index: -1;
  //}
  //
  //&::before {
  //  left: -343px;
  //  top: -240px;
  //}
  //
  //&::after {
  //  right: -456px;
  //  bottom: 0;
  //}

  .custom-container {
    position: relative;
    max-width: calc(1240px + 40px);
    width: 100%;

    z-index: 1;
    padding: 64px;
    @media (max-width: 767px) {
      padding: 32px 16px;
    }
    border-radius: 24px;
    background: #FFFFFA;
    box-shadow: 0px 24px 48px 0px rgba(16, 24, 40, 0.18);

    .row {
      display: flex;
      column-gap: 64px;
      row-gap: 32px;
      @media (max-width: 991px) {
        flex-wrap: wrap;
      }

      .content-box {
        width: 100%;
        max-width: 588px;

        .title {
          color: #06070E;
          font-family: "Montserrat", sans-serif;
          font-size: 32px;
          font-style: normal;
          font-weight: 600;
          line-height: normal;
          margin-bottom: 32px;
          @media (max-width: 767px) {
            font-size: 24px;
            margin-bottom: 24px;
            text-align: center;
          }
        }

        .feature-list {
          padding: 16px 32px;
          margin-bottom: 16px;
          display: flex;
          flex-direction: column;
          row-gap: 18px;
          @media (max-width: 767px) {
            row-gap: 12px;
            padding: 0;
            margin-bottom: 32px;
          }

          .feature-item {
            display: flex;
            align-items: center;
            column-gap: 20px;
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-size: 16px;
            font-style: normal;
            font-weight: 400;
            line-height: 135%;
            @media (max-width: 767px) {
              font-size: 12px;
              column-gap: 12px;
            }

            .feature-icon {
              width: 40px;
              height: 40px;
              @media (max-width: 767px) {
                width: 32px;
                height: 32px;
              }
              border-radius: 50%;
              -webkit-border-radius: 50%;
              -moz-border-radius: 50%;
              -ms-border-radius: 50%;
              -o-border-radius: 50%;

              svg, img {
                width: 40px;
                height: 40px;
                @media (max-width: 767px) {
                  width: 32px;
                  height: 32px;
                }
                object-fit: cover;
                object-position: center;
              }
            }
          }
        }

        .notice-box {
          padding: 8px 32px;
          @media (max-width: 767px) {
            padding: 0;
          }

          .subtitle,
          .desc {
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-size: 14px;
            font-style: normal;
            line-height: normal;
            @media (max-width: 767px) {
              font-size: 12px;
              text-align: center;
            }
          }

          .subtitle {
            font-weight: 600;
          }

          .desc {
            font-weight: 400;
          }
        }
      }

      .payment-box {
        width: 100%;
        max-width: 460px;
        @media (max-width: 767px) {
          padding: 16px 0;
        }

        .cart-payment-box {
          background-color: #ffff;
          border-radius: 11px 11px 21px 21px;
        }

        .cart-payment-title {
          font-family: 'Montserrat', sans-serif;
          font-size: 18px;
          font-style: normal;
          font-weight: 700;
          line-height: normal;
          text-transform: capitalize;
          letter-spacing: 0em;
          text-align: left;
          margin-bottom: 16px;
          @media screen and (min-width: 768px) {
            padding: 8px;
          }
        }

        .payment-option-list {
          margin: 0;
          padding: 0;
          list-style: none;
          width: 100%;
          max-width: 320px;
          display: flex;
          align-items: center;
          column-gap: 16px;
          margin-bottom: 20px;

          .payment-option-item {
            position: relative;
            display: flex;
            align-items: baseline;
            @media screen and (min-width: 768px) {
              margin-left: 4px;
            }

            label {
              color: #000;
              font-family: "Montserrat", sans-serif;
              font-size: 14px;
              font-style: normal;
              font-weight: 600;
              line-height: normal;
              text-transform: uppercase;
              padding: 4px;
              margin-left: 4px;
            }
          }
        }

        .my-card-list {
          height: 403px;
          overflow-y: auto;
          scrollbar-width: 4px;
          padding-right: 5px;
          @media (max-width: 389px) {
            padding-right: 3px;
          }

          &::-webkit-scrollbar {
            width: 4px;
          }

          &::-webkit-scrollbar-track {
            background: transparent;
          }

          &::-webkit-scrollbar-thumb {
            background: #C4C4C4;
            border-radius: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            -ms-border-radius: 10px;
            -o-border-radius: 10px;
          }

          .my-card-box {
            margin-bottom: 14px;
            opacity: .7;
            cursor: pointer;

            &.active {
              opacity: 1;
            }
          }
        }

        .core-premium-btn-box {
          padding: 0px 8px;
          display: flex;
          align-items: center;
          flex-wrap: wrap;
          column-gap: 16px;
          row-gap: 16px;
          margin-top: 24px;
          @media (max-width: 767px) {
            margin-top: 16px;
            row-gap: 8px;
            justify-content: center;
          }

          .btn-gPay {
            width: 224px;
            height: 42px;
            display: flex;
            padding: 12px 16px;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border-radius: 100px;
            background: #000;
            color: #FFF;
            font-family: "Montserrat", sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: 400;
            line-height: 16px;
            letter-spacing: -0.28px;
          }

          .desc {
            color: #06070E;
            font-family: "Montserrat", sans-serif;
            font-size: 12px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            @media (max-width: 767px) {
              text-align: center;
            }

            strong {
              font-weight: 700;
            }
          }
        }

        .credit-box {
          @media (min-width: 768px) {
            padding: 0 8px;
            margin-top: 24px;
          }
          @media (max-width: 767px) {
            margin-top: 20px;
          }

          .field-box {
            position: relative;
            cursor: pointer;
            margin-bottom: 16px;
            @media (max-width: 767px) {
              margin-bottom: 12px;
            }

            .input-label {
              position: absolute;
              top: 12px;
              left: 18px;
              color: #06070E;
              font-family: "Montserrat", sans-serif;
              font-size: 14px;
              font-style: normal;
              font-weight: 400;
              line-height: normal;
            }

            .input-field {
              display: block;
              width: 100%;
              padding: 12px 18px 13px;
              color: #06070E;
              font-family: "Montserrat", sans-serif;
              font-size: 14px;
              font-style: normal;
              font-weight: 400;
              line-height: normal;
              border: 1px solid rgba(#06070E, .4499);
              border-radius: 8px;
              -webkit-border-radius: 8px;
              -moz-border-radius: 8px;
              -ms-border-radius: 8px;
              -o-border-radius: 8px;
            }

            .icon-list {
              position: absolute;
              top: 8px;
              right: 16px;
              display: flex;
              align-items: center;
              justify-content: flex-end;
              column-gap: 4px;
              @media (max-width: 767px) {
                display: none;
              }

              .icon-img {
                width: 36px;
                height: 24px;
              }
            }

            &.focus {
              .input-label {
                top: -8px;
                padding: 0 6px;
                background-color: #fff;
                color: #690FAD;
              }

              .input-field {
                border-color: #690FAD;
              }
            }

            &.filled {
              .input-label {
                display: none;
              }

              .input-field {
                border-color: rgba(#690fad, .45);
              }
            }

            &:hover {
              .input-label {
                color: #690fad;
              }

              .input-field {
                border-color: #000;
              }
            }
          }

          .field-inner-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            column-gap: 24px;
            @media (max-width: 767px) {
              column-gap: 8px;
            }

            .field-box {
              margin-bottom: 0;
            }
          }
        }

        // .payment-mode-box{
        //     display: grid;
        //     grid-template-columns: 1fr 1fr;
        //     column-gap: 12px;
        //     row-gap: 12px;
        //     margin: 24px 0;
        //     @media (max-width:767px) {
        //         column-gap: 8px;
        //         row-gap: 8px;
        //         margin: 12px 0 16px;
        //     }
        //     .inputGroup {
        //         display: inline-block;
        //         position: relative;
        //         border-radius: 15px;
        //         box-shadow: 0px 2px 4px 0px #0000001A;
        //         label {
        //             padding: 12px 16px;
        //             width: 100%;
        //             height: 61px;
        //             display: flex;
        //             flex-direction: column;
        //             row-gap: 4px;
        //             text-align: left;
        //             cursor: pointer;
        //             position: relative;
        //             z-index: 2;
        //             overflow: hidden;
        //             border-radius: 8px;
        //             font-family: 'Montserrat', sans-serif;
        //             color: rgba(#06070E, 0.44999998807907104);
        //             font-size: 14px;
        //             font-style: normal;
        //             font-weight: 400;
        //             line-height: normal;
        //             border: 1px solid #000000;
        //             margin: 0;
        //             svg,path{
        //                 fill: #000;
        //             }
        //             .payment-mode-icon-box{
        //                 width: max-content;
        //                 border-radius: 100px;
        //                 border: 0.5px solid #06070E;
        //                 background: #FFF;
        //                 padding: 2px 8px;
        //             }
        //             &:after {
        //                 width: 10px;
        //                 height: 10px;
        //                 content: '';
        //                 border: 6px solid rgba(#06070E, 0.44999998807907104);
        //                 outline: 1px solid #000;
        //                 outline-offset: 4px;
        //                 background-color: #ffff;
        //                 border-radius: 50%;
        //                 z-index: 2;
        //                 position: absolute;
        //                 right: 16px;
        //                 top: 50%;
        //                 transform: translateY(-50%);
        //                 cursor: pointer;
        //             }
        //         }
        //         input:checked~label {
        //             padding: 13px 17px;
        //             color: #FFFFFA;
        //             border: none;
        //             background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
        //             svg,path{
        //                 fill: #fff;
        //             }
        //             &:before {
        //                 transform: translate(-50%, -50%) scale3d(56, 56, 1);
        //                 opacity: 1;
        //             }
        //             &:after {
        //                 border-color: #fff;
        //                 outline-color: #fff;
        //             }
        //         }
        //         input {
        //             width: 10px;
        //             height: 10px;
        //             order: 1;
        //             z-index: 2;
        //             position: absolute;
        //             right: 16px;
        //             top: 50%;
        //             transform: translateY(-50%);
        //             cursor: pointer;
        //             visibility: hidden;
        //         }
        //     }
        // }
        // .term-condition-box{
        //     display: flex;
        //     flex-direction: column;
        //     row-gap: 16px;
        //     .form-group {
        //         line-height: 1;
        //         padding: 0;
        //         label{
        //             color: var(--cd-black, #06070E);
        //             font-family: "Montserrat", sans-serif;
        //             font-size: 11px;
        //             font-style: normal;
        //             font-weight: 700;
        //             line-height: normal;
        //             &::after{
        //                 margin-left: 0;
        //             }
        //             &::before{
        //                 width: 14px;
        //                 height: 14px;
        //                 margin-right: 8px;
        //                 background: url("../../assets/images/checkbox-uncheck_black.png");
        //                 background-repeat: no-repeat;
        //                 top: -1px;
        //                 left: 1px;
        //                 padding: 0;
        //                 margin-left: 0;
        //             }
        //             a{
        //                 color: #690FAD;
        //                 text-decoration-line: underline;
        //             }
        //         }
        //         input:checked + label:after{
        //             top: -1px;
        //             @media (max-width:767px){
        //                 top: -2px;
        //             }
        //         }
        //     }
        // }
      }

      .payment-success-box {
        width: 100%;

        .icon-box {
          width: 64px;
          height: 64px;
          border-radius: 50%;
          -webkit-border-radius: 50%;
          -moz-border-radius: 50%;
          -ms-border-radius: 50%;
          -o-border-radius: 50%;
          margin-bottom: 24px;
          margin-left: auto;
          margin-right: auto;
          animation: zoom-in-zoom-out 1s ease;
          -webkit-animation: zoom-in-zoom-out 1s ease;
          @media(max-width: 767px) {
            width: 32px;
            height: 32px;
            margin-bottom: 12px;
          }

          svg,
          img {
            width: 100%;
            height: 100%;
          }
        }

        .title {
          padding: 8px;
          color: #000;
          text-align: center;
          font-family: "Montserrat", sans-serif;
          font-size: 18px;
          font-style: normal;
          font-weight: 700;
          line-height: normal;
          text-transform: capitalize;
          margin-bottom: 24px;
          animation: fade-in 2s ease;
          -webkit-animation: fade-in 2s ease;
          @media (max-width: 767px) {
            margin-bottom: 12px;
          }
        }

        .payment-received-box {
          border: 1px solid rgba(6, 7, 14, 0.45);
          border-radius: 8px;
          -webkit-border-radius: 8px;
          -moz-border-radius: 8px;
          -ms-border-radius: 8px;
          -o-border-radius: 8px;
          margin-bottom: 58px;
          animation: fade-in 2s ease;
          -webkit-animation: fade-in 2s ease;
          @media (max-width: 767px) {
            margin-bottom: 29px;
          }

          .payment-received-message {
            padding: 24px;
            border-bottom: 1px solid rgba(6, 7, 14, 0.45);
            color: #06070E;
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: 500;
            line-height: normal;
            @media (max-width: 767px) {
              padding: 12px;
            }
          }

          .payment-received-amount {
            color: #06070E;
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 20px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            padding: 17px 24px;
            @media (max-width: 767px) {
              padding: 17px 12px;
            }
          }
        }

        .payment-detail-box {
          padding: 8px 24px;
          border-radius: 8px;
          border: 1px solid rgba(6, 7, 14, 0.45);
          margin-bottom: 62px;
          display: flex;
          flex-direction: column;
          row-gap: 16px;
          animation: fade-in 2s ease;
          -webkit-animation: fade-in 2s ease;
          @media (max-width: 767px) {
            row-gap: 8px;
            margin-bottom: 32px;
          }

          .payment-detail-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            column-gap: 16px;
            @media (max-width: 767px) {
              column-gap: 8x;
            }

            .label,
            .value {
              color: #06070E;
              font-family: "Montserrat", sans-serif;
              font-size: 14px;
              font-style: normal;
              line-height: normal;
              @media (max-width: 767px) {
                font-size: 12px;
              }
            }

            .label {
              text-align: left;
              font-weight: 400;
            }

            .value {
              text-align: right;
              font-weight: 500;
            }
          }
        }

        .payment-success-btn-box {
          display: flex;
          align-items: center;
          justify-content: center;
          flex-wrap: wrap;
          column-gap: 16px;
          row-gap: 16px;
          animation: fade-in 3s ease;
          -webkit-animation: fade-in 3s ease;

          .btn-download {
            padding: 11px 24px;
            border-radius: 24px;
            border: 1px solid #690FAD;
            background: #FFFFFA;
            color: #690FAD;
            text-align: center;
            font-family: "Montserrat", sans-serif;
            font-size: 14px;
            font-style: normal;
            font-weight: 600;
            line-height: normal;
            @media (max-width: 767px) {
              width: 100%;
            }
          }

          .btn-core-premium-subscribe {
            @media (max-width: 767px) {
              width: 100%;
              justify-content: center;
            }
          }
        }
      }
    }
  }

  &.payment-success {
    .custom-container {
      background-color: transparent;

      .row {
        @media (max-width: 991px) {
          flex-direction: column-reverse;
        }
      }
    }
  }
}
</style>
