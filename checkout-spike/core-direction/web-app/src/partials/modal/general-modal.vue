<template>
  <div class="wrapper">
    <!-- tooltip-descriptive-hover -->
    <div :id="`${isWearableModal ? 'wearable-modal' : 'tooltip-descriptive-hover'}`"
         :class="`custom-modal m-auto overflow-y-auto ${modalIsOpen ? '' : 'hidden'}`"
    >
      <div class="modal-center">
        <div class="modal-outer-box">
          <div class="modal-inner-box">
            <div class="modal-header flex items-center justify-between">
              <button v-if="isShowPreviousButton" class="btn-prev flex items-center" @click="showPreviousModal">
                <chevron-black-back-icon />

                Previous
              </button>
              <div class="btn-modal-close ml-auto" @click="closeModal()">
                <close-icon />
              </div>
            </div>
            <div class="modal-body px-5">
              <slot name="modalBody" />
              <slot name="modalFooter" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import CloseIcon from "../../svgs/close-icon";
import ChevronBlackBackIcon from "@/svgs/chevron-black-back-icon";

export default {
    components: {
        ChevronBlackBackIcon,
        CloseIcon
    },
    props: {
        modalIsOpen: {
            type: Boolean,
            required: true,
        },
        isWearableModal: {
            type: Boolean,
            required: false,
            default: false,
        },
        modalHeading: {
            type: String,
            default: "",
        },
        modalTitle: {
            type: String,
            default: "",
        },
        isShowPreviousButton: {
            type: Boolean,
            required: false,
            default: false
        }

    },
    methods: {
        closeModal() {
            this.$emit("closeModal");
        },
        showPreviousModal() {
            this.$emit("previousModal");
        }

    }
};
</script>