<template>
  <div>
    <main id="main">
      <section id="cart">
        <div class="custom-container">
          <div class="cart-header mx-auto">
            <div class="progress-bar">
              <div :class="`booking-history-tab progress-status-box ${activeTab === 'payment' ? 'active' : ''}`">
                <div class="progress-status" />
                <button class="progress-status-label">
                  Payment
                </button>
              </div>
              <div
                :class="`payment-details-tab progress-status-box ${activeTab === 'confirmation' ? 'active' : ''}`"
              >
                <div class="progress-status" />
                <button class="progress-status-label">
                  Confirmation
                </button>
              </div>
            </div>
          </div>
          <keep-alive>
            <component
              :is="activeTab"
              :confirmation="confirmationMessage"
              :make-payment-info-getter="informAboutPaymentCounter"
              v-bind="{selectedPackage}"
              @getPostPaymentData="getMemberPackageInformationAfterPurchase"
            />
          </keep-alive>
        </div>
        <div class="cart-footer mx-auto custom-container">
          <button v-if="activeTab === 'payment'" :disabled="isDisabled"
                  class="btn-next rounded-full ml-auto continue_to_payment" @click="informAboutPayment"
          >
            Continue to payment
            <svg class="ml-2" fill="none" height="11" viewBox="0 0 21 11" width="21" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M14.8565 2.2704C14.5659 1.99024 14.5648 1.53494 14.854 1.25345C15.1432 0.971881 15.6131 0.970911 15.9036 1.251L19.7817 4.99035L19.7824 4.99103L19.8511 6.08174L19.8505 6.08225L15.973 9.82108C15.6436 10.1387 15.1121 10.1375 14.7842 9.8183C14.4545 9.49741 14.4558 8.97706 14.787 8.65771L14.8564 8.72969L14.787 8.6577L17.2124 6.31915H1.74219C1.28005 6.31915 0.9 5.95539 0.9 5.50005C0.9 5.0447 1.28005 4.68094 1.74219 4.68094H17.2123L14.787 2.34239L14.8565 2.2704ZM14.8565 2.2704L14.787 2.34239C14.4559 2.02304 14.4546 1.50269 14.7842 1.18179C15.1121 0.862538 15.6437 0.861466 15.973 1.17901L19.8464 4.9139M14.8565 2.2704L19.8464 4.9139M19.8464 4.9139L19.8511 4.91836L19.8464 4.9139ZM19.8519 4.91914L19.8512 4.91842L19.8519 4.91914ZM19.8519 4.91914C20.1828 5.23916 20.1822 5.76005 19.8533 6.07956L19.8519 4.91914Z"
                fill="#690FAD" stroke="#690FAD" stroke-width="0.2"
              />
            </svg>
          </button>
        </div>
      </section>
    </main>
  </div>
</template>

<script>
import payment from "../../partials/booking/single-booking/steps/payment";
import confirmation from "../../partials/booking/single-booking/steps/confirmation";
import {createPurchaseEvent} from "@/apiManager/facebookPixel";

export default {
  name: "PurchasePackage",
  components: {
    payment,
    confirmation
  },
  data() {
    return {
      confirmationMessage: {
        header: "Thank you for purchasing the package",
        description: "This confirms that you have purchased this package"
      },
      tabsValuesCollection: ["payment", "confirmation"],
      tabsValues: {
        PAYMENT: "payment",
        CONFIRMATION: "confirmation"
      },
      activeTab: "payment",
      selectedPackage: null,
      informAboutPaymentCounter: 0,
      isDisabled: false
    };
  },
  mounted() {
    this.activeTab = this.tabsValues.PAYMENT;
    this.fetchPreReqData();
  },
  methods: {
    fetchPreReqData() {
      this.fetchPackage();
    },
    fetchPackage() {
      this.oldApi("get",
        this.constants.getUrl("getPackage") + "/" + this.$route.params.packageId
      ).then((response) => {
        this.selectedPackage = response.data;
      });
    },
    informAboutPayment() {
      const continue_to_payment = document.querySelector(".continue_to_payment");
      continue_to_payment.classList.add("cursor-not-allowed");
      this.isDisabled = true;
      this.informAboutPaymentCounter++;
    },
    async getMemberPackageInformationAfterPurchase(memberPackage) {
      if (!memberPackage) {
        const continue_to_payment = document.querySelector(".continue_to_payment");
        continue_to_payment.classList.remove("cursor-not-allowed");
        this.isDisabled = false;
        return;
      }
      await createPurchaseEvent({
        "event_name": "Purchase",
        "custom_data": {
          "value": this.selectedPackage.price
        }
      });
      this.activeTab = this.tabsValues.CONFIRMATION;
    }
  }
};
</script>

<style src="../../common/css/single-package-booking.css"></style>
<style scoped>
#payment-form .form-row .__PrivateStripeElement {
  font-family: 'Montserrat', sans-serif !important;
  font-size: 11px !important;
  font-style: normal !important;
  font-weight: 400 !important;
  line-height: 13px !important;
  letter-spacing: 0em !important;
  text-align: left !important;
  margin-bottom: 10px !important;
  padding: 12px 13px !important;
  background: #F1F1F1 !important;
  border-radius: 11px !important;
}
</style>