<template>
  <div>
    <main id="main">
      <section id="cart">
        <div class="custom-container">
          <div class="cart-header mx-auto">
            <div class="progress-bar">
              <div
                :class="`package-membership-tab progress-status-box ${activeTab === tabsValues.PACKAGE ? 'active' : ''}`"
              >
                <div class="progress-status" />
                <button class="progress-status-label">
                  Select package
                </button>
              </div>
              <div
                :class="`booking-history-tab progress-status-box ${activeTab === tabsValues.PAYMENT ? 'active' : ''}`"
              >
                <div class="progress-status" />
                <button class="progress-status-label">
                  Payment
                </button>
              </div>
              <div
                :class="`payment-details-tab progress-status-box ${activeTab === tabsValues.CONFIRMATION ? 'active' : ''}`"
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
              @moveNextTab="moveNextTab"
            />
          </keep-alive>
        </div>
        <div class="cart-footer mx-auto">
          <button v-if="activeTab !== tabsValues.CONFIRMATION && tabsValuesCollection.indexOf(activeTab) > 0"
                  class="btn-back" @click="goBackToPrevTab"
          >
            <svg class="mr-2" fill="none" height="11" viewBox="0 0 21 11" width="21" xmlns="http://www.w3.org/2000/svg">
              <path
                d="M6.14355 2.31191C6.43407 2.03175 6.43519 1.57644 6.14603 1.29495C5.85684 1.01338 5.38688 1.01241 5.09639 1.29251L1.21832 5.03185L1.21765 5.03253L1.14894 6.12324L1.14947 6.12375L5.02702 9.86259C5.35641 10.1802 5.88793 10.179 6.21582 9.85981C6.54545 9.53892 6.54416 9.01856 6.213 8.69921L6.14358 8.7712L6.21299 8.69921L3.78762 6.36066H19.2578C19.7199 6.36066 20.1 5.9969 20.1 5.54155C20.1 5.08621 19.7199 4.72245 19.2578 4.72245H3.78765L6.21296 2.38389L6.14355 2.31191ZM6.14355 2.31191L6.21296 2.38389C6.54412 2.06454 6.54541 1.54419 6.21579 1.2233C5.88788 0.904042 5.35632 0.90297 5.02698 1.22052L1.15356 4.9554M6.14355 2.31191L1.15356 4.9554M1.15356 4.9554L1.1489 4.95986L1.15356 4.9554ZM1.14813 4.96065L1.14884 4.95992L1.14813 4.96065ZM1.14813 4.96065C0.817204 5.28067 0.817816 5.80156 1.14673 6.12107L1.14813 4.96065Z"
                fill="black" stroke="black" stroke-width="0.2"
              />
            </svg>
            Keep Browsing
          </button>

          <button v-if="activeTab === tabsValues.PAYMENT" :disabled="isDisabled"
                  class="btn-next rounded-full ml-auto continue_to_payment" @click="informAboutPayment"
          >
            continue to payment
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
import selectPackage from "../../../partials/booking/single-booking/steps/select-package";
import payment from "../../../partials/booking/single-booking/steps/payment";
import confirmation from "../../../partials/booking/single-booking/steps/confirmation";
import toastr from "toastr";
import {scrollToTopOnSameRoute} from "@/utils";
import {updateBookingCheckInCorePoints} from "@/apiManager/gamification";
import moment from "moment";
import {createPurchaseEvent} from "@/apiManager/facebookPixel";

export default {
  name: "Booking",
  components: {
    package: selectPackage,
    payment,
    confirmation
  },
  data() {
    return {
      confirmationMessage: {
        header: "Thank you for your booking",
        description: "This confirms your booking of the selected activity. Please feel free to share your booking with your network by selecting \"Share Your Activity\" or \"See Calendar\" to view all future bookings."
      },
      tabsValuesCollection: ["package", "payment", "confirmation"],
      tabsValues: {
        PACKAGE: "package",
        PAYMENT: "payment",
        CONFIRMATION: "confirmation"
      },
      activeTab: "package",
      isLogin: !!this.$store.getters.getStoreTokenGetters,
      selectedPackage: null,
      informAboutPaymentCounter: 0,
      isDisabled: false,
      isHitReserveActivity: false
    };
  },
  created() {
    window.addEventListener("beforeunload", this.unReservedActivity);
  },
  mounted() {
    this.activeTab = this.tabsValues.PACKAGE;
  },
  beforeDestroy() {
    window.removeEventListener("beforeunload", () => {
    });
    this.unReservedActivity();
  },
  methods: {
    unReservedActivity() {
      if (this.isHitReserveActivity) {
        this.oldApi("patch", this.constants.getUrl("cancelReserveActivity"));
      }
    },
    moveNextTab(data) {
      if (this.activeTab === this.tabsValues.PACKAGE) {
        this.selectedPackage = data;
        this.bookActivityHandler();
      }
      scrollToTopOnSameRoute();
    },
    goBackToPrevTab() {
      const currentTabIndex = this.tabsValuesCollection.indexOf(this.activeTab);
      if (currentTabIndex >= 1) {
        this.activeTab = this.tabsValuesCollection[currentTabIndex - 1];
      }
      scrollToTopOnSameRoute();
    },
    informAboutPayment() {
      const continue_to_payment = document.querySelector(".continue_to_payment");
      continue_to_payment.classList.add("cursor-not-allowed");
      this.isDisabled = true;
      this.informAboutPaymentCounter++;
      scrollToTopOnSameRoute();
    },
    bookActivityHandler() {
      if (this.selectedPackage.purchased) {
        return this.bookActivity();
      } else {
        return this.reserveActivity();
      }
    },
    bookActivity() {
      this.isHitReserveActivity = false;
      const payLoad = {
        package_id: this.selectedPackage.package_id,
        member_package_id: this.selectedPackage.member_package_id,
        schedule_detail_id: this.$route.params.scheduleId
      };
      return this.createActivity(payLoad)
        .catch((error) => {
          this.displayErrors(error);
        });
    },
    reserveActivity() {
      this.isHitReserveActivity = true;
      const payLoad = {
        package_id: this.selectedPackage.package_id,
        member_package_id: "N/A",
        schedule_detail_id: this.$route.params.scheduleId
      };
      return this.createActivity(payLoad)
        .catch((error) => {
          if (error.errors[0].error) {
            toastr.error(error.errors[0].error);
            return false;
          }
          if (error[0]?.response?.data && error[0].response.data.errors.length === 1) {
            const [fetchError] = error[0].response.data.errors;
            if (fetchError.error === "User slot is already reserved") {
              this.activeTab = this.tabsValues.PAYMENT;
            } else
              this.displayErrors(error);
          } else
            this.displayErrors(error);
        });
    },
    createActivity(payLoad) {
      return this.oldApi("post",
        this.constants.getUrl("bookActivityForUser"),
        payLoad
      ).then(async () => {
        if (this.selectedPackage.purchased) {
          await createPurchaseEvent({
            "event_name": "Purchase",
            "custom_data": {
              "value": this.selectedPackage.price
            }
          });
          let {email} = this.$store.getters.getStoreUserProfileGetters();
          await updateBookingCheckInCorePoints(
            this.getBookingPointsPayload(email, moment(this.$route.query.classDate).format("YYYY-MM-DD"))
          );
        }
        this.activeTab = this.selectedPackage.purchased ? this.tabsValues.CONFIRMATION : this.tabsValues.PAYMENT;
      });
    },
    displayErrors(error) {
      if (error?.errors[0].error) {
        toastr.error(error.errors[0].error);
        return false;
      }
      if (error[0]?.response?.data) {
        for (let errorElement of error[0].response.data.errors) {
          toastr.error(errorElement.error);
        }
      }
    },
    getMemberPackageInformationAfterPurchase(memberPackage) {
      if (!memberPackage) {
        const continue_to_payment = document.querySelector(".continue_to_payment");
        continue_to_payment.classList.remove("cursor-not-allowed");
        this.isDisabled = false;
        return;
      }
      this.selectedPackage.member_package_id = memberPackage.member_package_id;
      this.selectedPackage.purchased = true;
      this.bookActivity();
    },
    getBookingPointsPayload(email, startDate) {
      return {
        userEmails: [email],
        type: "BOOK_ACTIVITY",
        startDate: startDate,
      };
    },
  }
};
</script>

<style src="../../../common/css/single-package-booking.css"></style>
