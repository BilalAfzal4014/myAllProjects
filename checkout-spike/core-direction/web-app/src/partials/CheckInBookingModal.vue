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
                <div v-if="isBooking" class="booking-checkin-header">
                  <div class="logo-box">
                    <div class="logo-outer-box">
                      <div class="logo-inner-box">
                        <img :src="$data.qrCode" alt="">
                      </div>
                    </div>
                  </div>
                  <div class="checkin-detail">
                    <p class="booking-activity-name text-center">
                      {{ checkInData.facility }}
                    </p>
                    <p class="booking-activity-place text-center uppercase">
                      {{ checkInData.zone_title }}
                    </p>
                    <p class="booking-activity-place text-center uppercase">
                      {{ checkInData.activityName }}
                    </p>
                    <p class="booking-activity-type text-center">
                      {{ checkInData.activityName }}
                    </p>
                    <p class="booking-activity-instructor text-center">
                      with {{ checkInData.instructor_name }}
                    </p>
                  </div>
                  <div class="booking-activity-instruction-box">
                    <p class="booking-activity-instruction text-center">
                      Please ensure you arrive 15 minutes ahead of your class or activity and present this QR code for
                      Checkin.
                    </p>
                  </div>
                </div>

                <div v-if="isBooking" class="booking-checkin-body">
                  <div class="booking-checkin-body-outer-box">
                    <div class="booking-checkin-body-inner-box flex items-center content-between">
                      <div class="activity-detail-left-side-box">
                        <div class="activity-detail-row flex items-start">
                          <div class="activity-detail-box">
                            <p class="activity-detail-title uppercase">
                              Date
                            </p>
                            <p class="activity-detail-info uppercase mb-3">
                              {{ checkInData.class_date }}
                            </p>
                          </div>
                          <div class="activity-detail-box">
                            <p class="activity-detail-title uppercase">
                              Activity Time
                            </p>
                            <p class="activity-detail-info uppercase mb-3">
                              {{ checkInData.startTime }} - {{ checkInData.endTime }}
                            </p>
                          </div>
                        </div>
                        <div class="activity-detail-row flex items-start">
                          <div class="activity-detail-box">
                            <p class="activity-detail-title uppercase">
                              zone
                            </p>
                            <p class="activity-detail-info uppercase mb-3">
                              {{ checkInData.zone_title }}
                            </p>
                          </div>
                        </div>
                        <div class="activity-detail-address-box">
                          <p class="activity-detail-title uppercase">
                            Location
                          </p>
                          <p class="activity-detail-info uppercase">
                            {{ checkInData.address }}
                          </p>
                        </div>
                      </div>
                      <div class="activity-detail-right-side-box">
                        <div class="activity-detail-right-side-outer-box">
                          <div class="activity-detail-right-side-inner-box">
                            <img :src="checkInData.activityImage">
                            <p class="activity-detail-name uppercase text-center">
                              {{ checkInData.activityName }}
                            </p>
                            <p class="activity-detail-address uppercase text-center">
                              at {{ checkInData.address }}
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-if="isCancel" class="booking-cancelation-message-box">
                  <div class="booking-cancelation-message-icon-box">
                    <checkin-modal-information-icon />
                  </div>
                  <p class="booking-cancelation-message-title mx-auto text-center">
                    Are you sure?
                  </p>
                  <p class="booking-cancelation-message-desc mx-auto text-center">
                    Are you sure you want to cancel this booking?
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
                    If you’d like to re-book this activity please navigate to activity listings page, search and re-book
                    again.
                  </p>
                </div>
                <button v-if="isDashboard" class="booking-cancelation-success btn-modal-close rounded-full capitalize"
                        @click="goToDashboard"
                >
                  Go to Dashboard
                </button>
                <button v-if="!isCancelButtonHide"
                        class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize"
                        @click="cancelBooking(checkInData.id,checkInData.class_date)"
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
import qrCodeMixin from "@/mixin/qrCodeMixin";
import {hideBodyScroll} from "@/utils";
import CheckinModalInformationIcon from "@/svgs/checkin-modal/checkin-modal-information-icon";
import cancelledBookingMixin from "@/mixin/cancelledBookingMixin";
import moment from "moment";
import CloseIcon from "@/svgs/close-icon";
import Emitter from "tiny-emitter/instance";

export default {
    name: "CheckInBookingModal",
    components: {CloseIcon, CheckinModalInformationIcon},
    props: {
        checkInData: {
            type: Object,
            required: true,
        },
    },
    mixins: [qrCodeMixin, cancelledBookingMixin],
    data() {
        return {
            isCancelButtonHide: false,
            checkText: "check-in",
            isBooking: true,
            isDashboard: false,
            isCancel: false,
            cancelBookingText: "Cancel"
        };
    },
    methods: {
        closeModal() {
            this.$emit("close", "close");
            hideBodyScroll();
            this.isCancelButtonHide = false;
            window.location.reload();
        },
        async cancelBooking(scheduleDetailId, startDate) {
            if (this.isBooking) {
                this.isBooking = false;
                this.isCancel = true;
                this.cancelBookingText = "Yes, Cancel booking";
                return;
            }
            if (!this.isDashboard) {
                await this.cancelBookingActivity(scheduleDetailId, moment(startDate, "DD-MM-YYYY").format("YYYY-MM-DD"));
                this.isCancelButtonHide = true;
                this.isCancel = false;
                this.isDashboard = true;
                Emitter.emit("refetch_activity_diary_listing");
            }

        },
        goToDashboard() {
            hideBodyScroll();
            this.$router.push("/listing");
        },
    },

};
</script>

<style scoped>
.activity-detail-right-side-inner-box > img {
  max-width: 55px;
  position: relative;
  right: -50px;
  top: 0px;
}
</style>