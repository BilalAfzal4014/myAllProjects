import * as toastr from "toastr";
import { updateBookingCheckInCorePoints } from "@/apiManager/gamification";

const cancelledBookingMixin = {
  methods: {
    async cancelBookingActivity(scheduleDetailId, startDate) {
      try {
        let { email } = this.$store.getters.getStoreUserProfileGetters();
        let response = await this.oldApi(
          "post",
          this.constants.getUrl("cancelActivity"),
          this.getCancelBookingPayload(scheduleDetailId),
          true
        );

        toastr.success(response.data[0].message);
        await updateBookingCheckInCorePoints(
          this.getBookingPointsPayload(email, startDate)
        );
        this.isCancelButtonHide = true;
      } catch (error) {
        toastr.error(error);
      }
      this.isCancel = false;
      this.isCancelButtonHide = true;
      this.isDashboard = true;
      this.$emit("onCancelledBooking");
    },
    getBookingPointsPayload(email, startDate) {
      return {
        userEmails: [email],
        type: "CANCLE_ACTIVITY",
        startDate: startDate,
      };
    },
    getCancelBookingPayload(scheduleDetailId) {
      return {
        slot_id: scheduleDetailId,
      };
    },
  },
};

export default cancelledBookingMixin;
