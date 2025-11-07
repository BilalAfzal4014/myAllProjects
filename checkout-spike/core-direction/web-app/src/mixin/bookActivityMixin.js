import { updateBookingCheckInCorePoints } from "@/apiManager/gamification";
import { changeDateFormatYearMonthDate, hideBodyScroll } from "@/utils";
import Emitter from "tiny-emitter/instance";

const REGEX_REMOVE_ORDINAL = /(\d+)(st|nd|rd|th)/;
const bookActivityMixin = {
  methods: {
    async bookActivity(activity) {
      this.bookClass = activity;

      if (this.$store.getters.getStoreTokenGetters) {
        const userProfile = JSON.parse(localStorage.getItem("userProfile"));
        const { email } = userProfile;
        if (activity.isFree) {
          await this.bookFreeActivity(email, activity);
          return false;
        }

        hideBodyScroll();
        this.showModal = true;
        return false;
      }

      hideBodyScroll();
      Emitter.emit("sign_in_modal", "show sign in modal");
    },
    async bookFreeActivity(email, activity) {
      try {
        const { status } = await this.oldApi(
          "post",
          this.constants.getUrl("bookActivity"),
          this.getFreeClassPayload(activity.schedule_detail_id),
          true
        );
        if (status !== 200) return false;
        await updateBookingCheckInCorePoints(
          this.getBookingPointsPayload(
            email,
            this.convertEnglishDateFormat(activity.startDate)
          )
        );
        Emitter.emit(
          "social_sharing_modal",
          "booking",
          activity?.company_slug ?? this.company_slug
        );
        this.showSharingModal = true;
        this.$emit("onChangeActivityStatus", activity);
        toastr.success("Activity has been booked successfully.");
      } catch (error) {
        if (error.status === 400) {
          toastr.error(error.message);
          return false;
        }
      }
    },
    getBookingPointsPayload(email, startDate) {
      return {
        userEmails: [email],
        type: "BOOK_ACTIVITY",
        startDate: changeDateFormatYearMonthDate(startDate),
      };
    },
    getFreeClassPayload(scheduleDetailId) {
      return {
        package_id: "N/A",
        member_package_id: "N/A",
        schedule_detail_id: scheduleDetailId,
      };
    },
    hideSocialSharingModal(value) {
      this.showSharingModal = value;
    },
    closedModal() {
      const body = document.querySelector("body");
      body.classList.remove("overflow-y-hidden");
      this.showModal = false;
    },
    confirmModal() {
      this.closedModal();
      const body = document.querySelector("body");
      body.classList.add("overflow-y-hidden");
      this.isConfirm = true;
    },
    closeConfirmModal() {
      const body = document.querySelector("body");
      body.classList.remove("overflow-y-hidden");
      this.isConfirm = false;
    },
    convertEnglishDateFormat(dateString) {
      let dateObject = dateString.split(",")[1];
      const date = new Date(
        String(dateObject).replace(REGEX_REMOVE_ORDINAL, "$1")
      );
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, "0");
      const day = String(date.getDate()).padStart(2, "0");
      return `${year}-${month}-${day}`;
    },
  },
};
export default bookActivityMixin;
