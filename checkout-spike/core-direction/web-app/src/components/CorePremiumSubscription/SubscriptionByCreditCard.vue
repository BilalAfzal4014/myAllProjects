<template>
  <div class="payment-box">
    <div class="cart-payment-box">
      <p class="cart-payment-title">
        Payment information
      </p>
      <div class="credit-box">
        <div class="input-wrap">
          <label ref="cardNumberLabel" class="input-label"> Number on Card </label>
          <div id="card-number" ref="cardNumber" class="input-field" />
        </div>
        <div class="field-inner-box">
          <div class="input-wrap">
            <label ref="cardExpiryLabel" class="input-label"> Expiry Date </label>
            <div id="card-expiry" class="input-field" />
          </div>
          <div class="input-wrap">
            <label ref="cardCVCLabel" class="input-label"> Enter CVC </label>
            <div id="card-cvc" class="input-field" />
          </div>
        </div>
        <div id="card-coupon" class="mb-6">
          <div class="input-wrap">
            <label ref="cardCouponLabel" class="input-label"> Enter Coupon Code </label>
            <input ref="cardCoupon" v-model.trim="couponCode" class="input-field" placeholder="e.g CORE30"
                   type="text" @focus="changeFocuscardCoupon"
                   @input="toUpper"
            >
          </div>
          <button class="ml-2" @click.prevent="couponApplied">
            Apply Coupon
          </button>
        </div>
        <div class="term-condition-box">
          <div id="payment-request-button" class="mb-4" />
          <div class="form-group">
            <input id="term-condition" v-model="isCheckedTerms" type="checkbox">
            <label for="term-condition">I agree to Core Direction’s <a
              href="https://coredirection.com/terms-and-conditions" target="_blank"
            >Terms of Services</a> and <a
              href="https://coredirection.com/privacy-policy" target="_blank"
            >Privacy Policy</a></label>
          </div>
        </div>
        <div class="core-premium-btn-box">
          <SubscriptionByCreditCardBtn :price="packagePrice" @click.native="createToken" />
          <div class="subscription-note">
            <p class="desc">
              Auto-renew Monthly {{ packageData?.price }}
            </p>
            <p class="desc">
              <strong>Cancel anytime</strong>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import * as toastr from "toastr";
import {getPackageDetail, subscribeCorePremiumPlan, validateCoupon} from "@/apiManager/core-premium.js";
import SubscriptionByCreditCardBtn from "./SubscriptionByCreditCardBtn.vue";
import {nextMonth, todayDate} from "../../utils/dateUtil";

export default {
  name: "SubscriptionByCreditCard",
  props: {
    packageData: {
      type: Object,
      default: () => {
      },
    }
  },
  data() {
    return {
      token: null,
      cardNumber: null,
      cardExpiry: null,
      cardCvc: null,
      isCheckedTerms: false,
      isCheckedSaveCard: false,
      couponCode: "",
      isValid: false,
      packagePrice: this.packageData?.price ?? 0,
    };
  },
  components: {
    SubscriptionByCreditCardBtn
  },
  computed: {
    stripeElements() {
      return this.$stripe.elements();
    },

  },
  methods: {
    toUpper() {
      this.couponCode = this.couponCode.toUpperCase();
    },
    async createPaymentRequestButton() {
      try {
        const paymentRequest = this.$stripe.paymentRequest({
          country: "AE",
          currency: "aed",
          total: {
            label: "Total",
            amount: this.calculateTotalAmount(),
          },
          requestPayerName: true,
          requestPayerEmail: true,
        });

        const canMakePayment = await paymentRequest.canMakePayment();
        if (canMakePayment) {
          this.mountPaymentButton(paymentRequest);
        } else {
          this.hidePaymentButton();
        }

        paymentRequest.on("paymentmethod", async (event) => {
          const {paymentMethod, complete, payerName, payerEmail} = event;
          complete("success");
        });
        paymentRequest.on("token", async function (event) {
          const response = await this.subscribeToPlan(event.token.id);
          if (response.statusCode === 201 && response.data) {
            this.$emit("successfullyPremium", {
              startDate: todayDate(),
              endDate: nextMonth(),
              network: event.token.card.last4
            });
          } else {
            this.displayError("Something went wrong");
          }
        });
      } catch (error) {
        this.displayError("Error creating payment request:" + error);
      }
    },

    calculateTotalAmount() {
      return 1 * 100;
    },

    mountPaymentButton(paymentRequest) {
      const prButton = this.stripeElements.create("paymentRequestButton", {
        paymentRequest,
      });
      prButton.mount("#payment-request-button");
    },

    hidePaymentButton() {
      document.getElementById("payment-request-button").style.display = "none";
    },
    async createToken() {
      const {token, error} = await this.$stripe.createToken(this.cardNumber);

      if (error) {
        return this.displayError(error.message);
      }

      if (!this.isCheckedTerms) {
        return this.displayError("Please accept terms & conditions");
      }

      await this.handleSubscription(token);
    },

    async handleSubscription(token) {
      try {
        const response = await this.subscribeToPlan(token.id);
        this.processSubscriptionResponse(response);
      } catch (error) {
        this.displayError(error.message);
      }
    },

    async subscribeToPlan(tokenId) {
      const packageId = this.packageData?.id ?? 1;
      const couponCode = this.isValid ? this.couponCode : "";
      return await subscribeCorePremiumPlan(tokenId, packageId, null, this.isCheckedSaveCard, couponCode);
    },

    processSubscriptionResponse(response) {
      if (response.statusCode === 201 && response.data) {
        this.emitSubscriptionSuccess(response.data);
        this.updateUserPremiumStatus();
      } else if (response.statusCode === 402 && response.data) {
        this.displayError(response.data.message);
      }
    },

    emitSubscriptionSuccess(data) {
      this.$emit("successfullyPremium", {...data, success: true});
    },

    updateUserPremiumStatus() {
      const userData = JSON.parse(localStorage.getItem("userProfile"));
      localStorage.setItem("userProfile", JSON.stringify({...userData, isPremiumUser: true}));
      this.$store.commit("setIsCorePremium", true);
    },

    displayError(message) {
      toastr.error(message);
    },

    async couponApplied() {
      if (this.couponCode) {
        try {
          const res = await validateCoupon({code: this.couponCode});
          if (res?.statusCode === 200 && res?.data) {
            toastr.success("Successfully Coupon Is Availed");
            this.isValid = true;
            this.packagePrice = this.packageData?.price;
            if (res.data?.type === "flat") {
              this.packagePrice = this.packagePrice - res.data?.discount;
            } else if (res.data?.type === "percentage") {
              this.packagePrice = (this.packagePrice - ((res.data?.discount * this.packagePrice) / 100)).toFixed(2);
            }

          } else {
            this.isValid = false;
            this.packagePrice = this.packageData?.price;
          }
        } catch (e) {
          this.isValid = false;
          this.packagePrice = this.packageData?.price;
          toastr.error(e.message);

        }
      } else {
        toastr.error("Please Enter The Code");
      }
    },
    changeFocuscardCoupon() {
      this.$refs.cardCouponLabel.classList.add("focus");
      this.$refs.cardCoupon.style.border = "1px solid #690FAD";
      this.$refs.cardCoupon.placeholder.style.color = "RED";
    },
    async packageDetail() {
      try {
        const res = await getPackageDetail();
        if (res?.statusCode === 200 && res?.data) {
          this.packageData = res.data;
          this.packagePrice = res.data?.price;
        }
      } catch (e) {
        return e.message;
      }
    },

  },
  mounted() {
    const style = {
      base: {
        color: "#06070E",
        padding: "20px",
        fontFamily: "'Helvetica Neue', Helvetica, sans-serif",
        fontSmoothing: "antialiased",
        fontSize: "14px",
        "::placeholder": {
          color: "transparent",
        },
        ":focus": {
          "::placeholder": {
            color: "#06070E",

          },
        },
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a",
      },
    };

    let commonStyle = (border) => {
      return "padding: 16px 18px 13px; display: block !important; position: relative ; opacity: 1; border-radius: 8px;" + border;
    };
    this.cardNumber = this.stripeElements.create("cardNumber", {
      style,
      placeholder: "0000 0000 0000 0000",
      showIcon: true
    });
    this.cardNumber.mount("#card-number");
    this.cardExpiry = this.stripeElements.create("cardExpiry", {style});
    this.cardExpiry.mount("#card-expiry");
    this.cardCvc = this.stripeElements.create("cardCvc", {style});
    this.cardCvc.mount("#card-cvc");
    this.cardNumber._component.style.cssText = commonStyle("border:1px solid #616264;");
    this.cardCvc._component.style.cssText = commonStyle("border:1px solid #616264;");
    this.cardExpiry._component.style.cssText = commonStyle("border:1px solid #616264;");
    this.cardNumber.on("focus", () => {
      this.cardNumber._component.style.cssText = commonStyle("border:1px solid #690FAD;");

      this.$refs.cardNumberLabel.classList.add("focus");

    });
    this.cardCvc.on("focus", () => {
      this.cardCvc._component.style.cssText = commonStyle("border:1px solid #690FAD;");
      this.$refs.cardCVCLabel.classList.add("focus");
    });
    this.cardExpiry.on("focus", () => {
      this.cardExpiry._component.style.cssText = commonStyle("border:1px solid #690FAD;");

      this.$refs.cardExpiryLabel.classList.add("focus");
    });
    this.packageDetail();
    this.createPaymentRequestButton();


  },
  beforeDestroy() {
    this.cardNumber.destroy();
    this.cardExpiry.destroy();
    this.cardCvc.destroy();
  },
};
</script>

<style lang="scss" scoped>

.term-condition-box {
  padding: 0px 8px;
  display: flex;
  flex-direction: column;
  row-gap: 16px;

  .form-group {
    line-height: 1;
    padding: 0;

    label {
      color: var(--cd-black, #06070E);
      font-family: "Montserrat", sans-serif;
      font-size: 11px;
      font-style: normal;
      font-weight: 700;
      line-height: normal;

      &::after {
        margin-left: 0;
      }

      &::before {
        width: 14px;
        height: 14px;
        margin-right: 8px;
        background: url("../../assets/images/checkbox-uncheck_black.png");
        background-repeat: no-repeat;
        top: -1px;
        left: 1px;
        padding: 0;
        margin-left: 0;
      }

      a {
        color: #690FAD;
        text-decoration-line: underline;
      }
    }

    input:checked + label:after {
      top: -1px;
      @media (max-width: 767px) {
        top: -2px;
      }
    }
  }
}

.input-label {
  position: absolute;
  top: 14px;
  left: 18px;
  color: #06070E;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
  line-height: normal;
  background: #ffff;

}

.input-wrap {
  position: relative;
  margin-bottom: 16px;

  &:hover .input-labe {
    color: #690FAD;
  }
}

#card-coupon {
  position: relative;
  display: flex;
  justify-content: space-between;
  height: 48px;

  .input-wrap {
    width: 60%;
    @media (max-width: 767px) {
      width: 100%;
      margin-top: 6px;
    }
  }

  input {
    padding: 12px 16px;
    opacity: 1;
    border-radius: 8px;
    border: 1px solid rgb(97, 98, 100);
    display: block;
    width: 100%;
    font-weight: 300;

    &::placeholder {
      color: transparent
    }

    &:focus::placeholder {
      color: #06070E;
    }

  }

  button {
    display: flex;
    align-items: center;
    column-gap: 10px;
    color: #FFFFFA;
    text-align: center;
    font-family: "Montserrat", sans-serif;
    font-size: 14px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
    padding: 6px 32px;
    background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);
    border-radius: 30px;
    -webkit-border-radius: 30px;
    -moz-border-radius: 30px;
    -ms-border-radius: 30px;
    -o-border-radius: 30px;
    width: max-content;

    &:hover {
      background: linear-gradient(270.17deg, rgba(93, 13, 153, .65) 15.31%, #7812c6 99.74%);
    }

    &:active {
      opacity: .65;
      background: linear-gradient(272.52deg, #5d0d99 16.63%, rgba(120, 18, 198, .65) 49.42%, #5d0d99 83.46%);
    }

    @media (max-width: 767px) {
      padding: 12px 32px;
      margin: 0 auto 4px auto;
      width: 226px;
      justify-content: center;
    }
  }

  @media (max-width: 767px) {
    flex-direction: column;
    height: auto;
  }
}

.focus {
  top: -8px;
  padding: 0 6px;
  background-color: #ffff;
  color: #690FAD;
  z-index: 100;
}

.field-inner-box {
  display: grid;
  grid-template-columns: 1fr 1fr;
  column-gap: 24px;
  margin-top: 20px;
  @media (max-width: 767px) {
    column-gap: 8px;
  }

  .field-box {
    margin-bottom: 0;
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
        border: 1px solid rgba($color: #06070E, $alpha: .4499);
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

  .payment-mode-box {
    display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 12px;
    row-gap: 12px;
    margin: 24px 0;
    @media (max-width: 767px) {
      column-gap: 8px;
      row-gap: 8px;
      margin: 12px 0 16px;
    }

    .inputGroup {
      display: inline-block;
      position: relative;
      border-radius: 15px;
      box-shadow: 0px 2px 4px 0px #0000001A;

      label {
        padding: 12px 16px;
        width: 100%;
        height: 61px;
        display: flex;
        flex-direction: column;
        row-gap: 4px;
        text-align: left;
        cursor: pointer;
        position: relative;
        z-index: 2;
        overflow: hidden;
        border-radius: 8px;
        font-family: 'Montserrat', sans-serif;
        color: rgba($color: #06070E, $alpha: 0.44999998807907104);
        font-size: 14px;
        font-style: normal;
        font-weight: 400;
        line-height: normal;
        border: 1px solid #000000;
        margin: 0;

        svg, path {
          fill: #000;
        }

        .payment-mode-icon-box {
          width: max-content;
          border-radius: 100px;
          border: 0.5px solid #06070E;
          background: #FFF;
          padding: 2px 8px;
        }

        &:after {
          width: 10px;
          height: 10px;
          content: '';
          border: 6px solid rgba(#06070E, 0.44999998807907104);
          outline: 1px solid #000;
          outline-offset: 4px;
          background-color: #ffff;
          border-radius: 50%;
          z-index: 2;
          position: absolute;
          right: 16px;
          top: 50%;
          transform: translateY(-50%);
          cursor: pointer;
        }
      }

      input:checked ~ label {
        padding: 13px 17px;
        color: #FFFFFA;
        border: none;
        background: linear-gradient(227deg, #7812C6 15.11%, rgba(93, 13, 153, 0.65) 100%);

        svg, path {
          fill: #fff;
        }

        &:before {
          transform: translate(-50%, -50%) scale3d(56, 56, 1);
          opacity: 1;
        }

        &:after {
          border-color: #fff;
          outline-color: #fff;
        }
      }

      input {
        width: 10px;
        height: 10px;
        order: 1;
        z-index: 2;
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        visibility: hidden;
      }
    }
  }
}
</style>