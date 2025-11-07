<template>
  <GeneralModal :is-show-previous-button="hasPaginationAndPreviousButton"
                :is-wearable-modal="true"
                :modal-is-open="showWearableModal"
                @closeModal="closeModal"
                @previousModal="showPreviousModal"
  >
    <template v-slot:modalBody>
      <component :is="currentModal" @closeModal="closeModal"
                 @showModal="showModal"
      />
    </template>
    <template v-if="hasPaginationAndPreviousButton" v-slot:modalFooter>
      <div class="wearable-footer flex items-center justify-between">
        <button class="btn-skip" @click="skipNextSlides">
          Skip
        </button>
        <div class="wearable-pagination-list flex items-center justify-center">
          <button v-for="(item, index) in paginationItems" :key="index" :class="item.classes" />
        </div>
        <button class="btn-next flex items-center" @click="nextSlide">
          Next
          <next-blue-icon />
        </button>
      </div>
    </template>
  </GeneralModal>
</template>
<script>
import WearableModalOne from "@/components/community/wearable/WearableModalOne.vue";
import WearableModalTwo from "@/components/community/wearable/WearableModalTwo.vue";
import WearableModalThree from "@/components/community/wearable/WearableModalThree.vue";
import WearableModalFour from "@/components/community/wearable/WearableModalFour.vue";
import WearableModalFive from "@/components/community/wearable/WearableModalFive.vue";
import WearableModalSix from "@/components/community/wearable/WearableModalSix.vue";
import WearableModalSeven from "@/components/community/wearable/WearableModalSeven.vue";
import GeneralModal from "@/partials/modal/general-modal";
import NextBlueIcon from "@/svgs/next-blue-icon";

export default {
    name: "WearableModal",
    components: {
        NextBlueIcon,
        GeneralModal,
        WearableModalOne, WearableModalTwo, WearableModalThree, WearableModalFour, WearableModalFive, WearableModalSix,
        WearableModalSeven
    },
    props: {
        showWearableModal: {
            type: Boolean,
            required: true
        }
    },
    data() {
        return {
            currentModalNumber: 1,
            wearableModals: [
                WearableModalOne,
                WearableModalTwo,
                WearableModalThree,
                WearableModalFour,
                WearableModalFive,
                WearableModalSix,
                WearableModalSeven,
            ]
        };
    },
    computed: {
        paginationItems() {
            const maxItems = this.wearableModals.length - 2;
            const items = [];
            for (let i = 1; i <= maxItems; i++) {
                const isActive = this.currentModalNumber === i + 1;
                const classes = [
                    "wearable-pagination-item",
                    "rounded-full",
                    isActive && "active",
                ];
                items.push({classes});
            }
            return items;
        },
        hasPaginationAndPreviousButton() {
            return this.currentModalNumber >= 2 && this.currentModalNumber !== 7;
        },
        currentModal() {
            return this.wearableModals[this.currentModalNumber - 1];
        },

    },
    methods: {
        skipNextSlides() {
            this.currentModalNumber = 1;
        },
        nextSlide() {
            this.currentModalNumber += 1;
        },
        closeModal() {
            this.currentModalNumber = 1;
            this.$emit("closeWearableModal");
        },
        showModal(newVal) {
            this.currentModalNumber = newVal;
        },
        showPreviousModal() {
            this.showModal(this.currentModalNumber - 1);
        },
        loadConnectWearableModal() {
            this.currentModalNumber = this.wearableModals.length;
        }
    }
};
</script>

<style>
#wearable-modal .modal-header {
  padding-top: 13px;
  padding-right: 13px;
  padding-bottom: 0;
}

#wearable-modal .modal-header svg,
#wearable-modal .modal-header path {
  fill: #000000;
}

#wearable-modal .modal-header .btn-prev {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
  margin-left: 10px;
}

#wearable-modal .modal-header .btn-prev svg {
  margin-right: 10px;
}

#wearable-modal .modal-outer-box {
  max-width: 650px;
  background: #F1F1F1;
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 21px;
}
@media (min-width: 767px) {
  #wearable-modal .modal-outer-box {
    max-width: clamp(325px, 41.139vw, 650px);
  }
}

#wearable-modal .form-container {
  width: 100%;
  max-width: 560px;
}

#wearable-modal .score-point-list {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
  margin-top: clamp(30px, 6.813vh, 60px);
  margin-bottom: clamp(36px, 8.375vh, 72px);
}

#wearable-modal .score-point-item.active svg,
#wearable-modal .score-point-item.active path {
  fill: #690FAD;
}

#wearable-modal .wearable-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 30px;
  font-weight: 700;
  line-height: 37px;
  letter-spacing: 0em;
  text-align: center;
  color: #06070E;
  margin-top: clamp(20.5px, 4.339vh, 41px);
}

#wearable-modal .wearable-track-list {
  margin-top: clamp(28.5px, 6.422vh, 57px);
  margin-bottom: clamp(23px, 4.99vh, 46px);
  -webkit-column-gap: 30px;
  column-gap: 30px;
  row-gap: 30px;
}

#wearable-modal .wearable-subtitle {
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 24px;
  letter-spacing: -0.011em;
  text-align: center;
  margin-bottom: clamp(7.5px, 1.853vh, 15px);
  color: #06070E;
}

#wearable-modal .wearable-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 21px;
  letter-spacing: -0.011em;
  text-align: center;
  color: #06070E;
  max-width: 430px;
}

#wearable-modal .wearable-btn-box {
  width: 100%;
  max-width: 360px;
  row-gap: 10px;
  margin-bottom: clamp(45.5px, 10.849vh, 91px);
}

#wearable-modal .btn-warable-action {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  padding: 15px;
  border: 1px solid #690FAD;
  color: #690FAD;
}

#wearable-modal .btn-warable-action:hover {
  color: #FFFFFF;
  background: #690FAD;
}

#wearable-modal .btn-warable-action.active {
  color: #FFFFFF;
  background: #690FAD;
}

#wearable-modal .btn-warable-action.active:hover {
  color: #690FAD;
  background: #FFFFFF;
}

#wearable-modal .activity-box {
  row-gap: 9px;
  margin-top: clamp(22px, 4.729vh, 44px);
  margin-bottom: clamp(30.5px, 6.943vh, 61px);
}

#wearable-modal .activity-icon-box {
  margin: auto;
  max-width: 54px;
  max-height: 54px;
}

#wearable-modal .activity-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 400;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .activity-points {
  font-family: 'Montserrat', sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .activity-duration {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .point-scoring-box {
  width: 100%;
  max-width: 360px;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: clamp(15px, 3.806vh, 30px);
}

#wearable-modal .point-scoring-title {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 21px;
  letter-spacing: -0.011em;
  text-align: center;
  margin-bottom: 5px;
  text-transform: uppercase;
}

#wearable-modal .point-scoring-desc {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: center;
  margin-bottom: clamp(9px, 2.244vh, 18px);
}

#wearable-modal .point-scoring-list {
  -webkit-column-gap: 7.77px;
  column-gap: 7.77px;
  row-gap: 8px;
}

#wearable-modal .wearable-footer {
  margin-top: clamp(36.5px, 8.505vh, 73px);
  margin-bottom: clamp(18.5px, 3.818vh, 37px);
}

#wearable-modal .btn-skip {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .wearable-pagination-list {
  -webkit-column-gap: 10px;
  column-gap: 10px;
}

#wearable-modal .wearable-pagination-item {
  min-width: 12px;
  max-width: 12px;
  min-height: 12px;
  max-height: 12px;
  background: #DADADA;
}

#wearable-modal .wearable-pagination-item.active {
  background: #690FAD;
}

#wearable-modal .btn-next {
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: center;
  color: #690FAD;
}

#wearable-modal .btn-next svg {
  margin-left: 10px;
}

#wearable-modal .point-scoring-content-box {
  width: 47px;
}

#wearable-modal .point-scoring-value {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
  margin-top: 5px;
}

#wearable-modal .point-scoring-label {
  font-family: 'Montserrat', sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: center;
}

#wearable-modal .point-scoring-step-list {
  margin-bottom: clamp(90px, 21.438vh, 180px);
}

@media (min-width: 768px) {
  #wearable-modal .point-scoring-step-list {
    margin-top: clamp(30px, 6.813vh, 60px);
  }
}

#wearable-modal .btn-connect-wearable-box {
  margin-top: clamp(54px, 13.063vh, 108px);
}

@media (max-width: 389px) {
  #wearable-modal .wearable-title {
    font-size: 26px;
    line-height: 30px;
    margin-top: 30px;
  }

  #wearable-modal .wearable-track-list {
    margin-top: 46px;
    margin-bottom: 30px;
    -webkit-column-gap: 20px;
    column-gap: 20px;
    row-gap: 20px;
  }

  #wearable-modal .score-point-list {
    margin-top: 34px;
    margin-bottom: 44px;
  }

  #wearable-modal .btn-warable-action {
    line-height: 15px;
    padding: 12px;
  }

  #wearable-modal .wearable-btn-box {
    margin-bottom: 71px;
  }

  #wearable-modal .activity-box {
    margin-top: 34px;
    margin-bottom: 41px;
  }

  #wearable-modal .wearable-footer {
    margin-top: 53px;
  }

  #wearable-modal .point-scoring-step-list {
    margin-bottom: 90px;
  }

  #wearable-modal .btn-connect-wearable-box {
    margin-top: 54px;
  }
}
</style>