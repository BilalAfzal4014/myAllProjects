<template>
  <div class="form-container mx-auto">
    <div id="checkin-modal">
      <div class="form-container mx-auto">
        <div class="booking-cancelation-message-box">
          <div class="booking-cancelation-message-icon-box">
            <checkin-modal-information-icon />
          </div>
          <p class="booking-cancelation-message-title mx-auto text-center">
            Are you sure you want to leave this group?
          </p>
        </div>

        <button class="booking-checkin-mode-btn-inspiring rounded-full capitalize" @click="$emit('leaveGroup')">
          Leave Group
        </button>
        <button class="booking-checkin-mode-btn-cancel btn-modal-close rounded-full capitalize"
                @click="$emit('closeModal')"
        >
          Cancel
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import CheckinModalInformationIcon from "@/svgs/checkin-modal/checkin-modal-information-icon";

export default {
    name: "LeaveGroupModalConfirmation",
    components: {CheckinModalInformationIcon},
};
</script>

<style scoped src="../assets/group-leave-modal.css">


</style>