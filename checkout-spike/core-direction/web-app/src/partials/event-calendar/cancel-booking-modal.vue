<template>
  <div class="wrapper">
    <div id="checkin-modal" class="custom-modal m-auto hidden overflow-y-auto" style="display: block;">
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header">
              <div class="btn-modal-close ml-auto" @click="closeModal">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <div class="form-container mx-auto">
                <div v-if="isCancel" class="booking-cancelation-message-box">
                  <div class="booking-cancelation-message-icon-box">
                    <checkin-modal-information-icon />
                  </div>
                  <p class="booking-cancelation-message-title mx-auto text-center">
                    Are you sure?
                  </p>
                  <p class="booking-cancelation-message-desc mx-auto text-center">
                    Are you sure you want to cancel this
                    booking?
                  </p>
                </div>
                <div v-if="isDashboard" class="booking-cancelation-message-box">
                  <div class="booking-cancelation-message-icon-box">
                    <checkin-modal-information-icon />
                  </div>
                  <p class="booking-cancelation-success-title mx-auto text-center">
                    You’ve cancelled your booking
                  </p>
                  <p class="booking-cancelation-success-desc mx-auto text-center">
                    If you’d like to re-book this activity
                    please navigate to activity listings page, search and re-book again.
                  </p>
                </div>
                <button v-if="isDashboard" class="booking-cancelation-success btn-modal-close rounded-full capitalize"
                        @click="goToDashboard"
                >
                  Go to Dashboard
                </button>
                <button v-if="!isCancelButtonHide"
                        class="booking-cancelation-success btn-modal-close rounded-full capitalize" @click="closeModal"
                >
                  No, I'll keep inspiring
                </button>
                <button v-if="!isCancelButtonHide"
                        class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize"
                        @click="cancelBooking(activityDetail.id, activityDetail.class_date)"
                >
                  {{ cancelBookingText }}
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
import cancelledBookingMixin from "@/mixin/cancelledBookingMixin";
import CheckinModalInformationIcon from "@/svgs/checkin-modal/checkin-modal-information-icon";
import CloseIcon from "@/svgs/close-icon";
import {cancelActivity} from "@/apiManager/diary";
import moment from "moment";

export default {
  name: "CancelBookingModal",
  components: {CloseIcon, CheckinModalInformationIcon},
  props: {
    activityDetail: {
      type: Object,
      required: true
    },
  },
  mixins: [cancelledBookingMixin],
  data() {
    return {
      isCancelButtonHide: false,
      isDashboard: false,
      isCancel: true,
      cancelBookingText: "Cancel"
    };
  },
  methods: {
    closeModal() {
      this.$emit("close", "close");
      document.querySelector("body").classList.remove("overflow-y-hidden");
      this.isCancelButtonHide = false;
    },
    async cancelBooking(scheduleDetailId, startDate) {
      try {
        if (this.isActivityCreatedByUser() || this.isUserActivityDairy()) {
          await this.cancelUserActivity();
        } else {
          await this.cancelBookingActivity(scheduleDetailId, this.formatStartDate(startDate));
        }
        this.postCancelActions();
      } catch (error) {
        toastr.error("An error occurred while cancelling the booking.");
      }
    },
    isActivityCreatedByUser() {
      return this.activityDetail.hasOwnProperty("created_by");
    },

    isUserActivityDairy() {
      return this.activityDetail.hasOwnProperty("type") && this.activityDetail.type === "userActivityDairy";
    },

    async cancelUserActivity() {
      const {statusCode} = await cancelActivity(this.activityDetail.id);
      if (statusCode === 204) toastr.success("Activity has been cancelled successfully.");
    },

    formatStartDate(startDate) {
      return moment(startDate, "DD-MM-YYYY").format("YYYY-MM-DD");
    },

    postCancelActions() {
      this.isCancelButtonHide = true;
      this.isCancel = false;
      this.isDashboard = true;
      this.$emit("cancelledBooking");
    },
    goToDashboard() {
      document.querySelector("body").classList.remove("overflow-y-hidden");
      this.$router.push("/listing");
    }
  },
};
</script>

<style scoped>
.booking-cancelation-message-box {
  background-color: #ffffff;
  margin-top: 67px;
  margin-bottom: 67px !important;
  padding: 49px 15px 68px;
  border-radius: 11px 11px 21px 21px;
  -webkit-box-shadow: 0px 22px 40px rgb(0 0 0 / 10%);
  box-shadow: 0px 22px 40px rgb(0 0 0 / 10%);
}

#checkin-modal .booking-cancelation-success {
  color: #ffffff;
  background: #690fad;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 16.41px;
  text-align: center;
  width: 100%;
  max-width: 372px;
  height: 48px;
  display: block;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 20px;
}

#checkin-modal .booking-checkin-mode-btn-cancel {
  background-color: #757575 !important;
}
</style>