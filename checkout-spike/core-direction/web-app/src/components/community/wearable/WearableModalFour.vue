<template>
  <div class="modal-body px-5">
    <div class="form-container mx-auto">
      <div class="activity-box flex items-center justify-center flex-col">
        <div class="activity-icon-box">
          <img alt="" class="activity-icon" src="/assets/images/wearable/activity-icon-03.png">
        </div>
        <div class="activity-info-box">
          <p class="activity-name">
            Steps
          </p>
          <p class="activity-points">
            10 Points Max
          </p>
          <p class="activity-duration">
            Per Day
          </p>
        </div>
      </div>
      <div class="point-scoring-box">
        <p class="wearable-subtitle">
          EVERY STEP COUNTS
        </p>
        <p class="wearable-desc">
          Every +1000 steps will award +1 Core Point up to a daily 10 Core Point maximum.
        </p>
      </div>
      <ul class="point-scoring-list point-scoring-step-list flex items-center justify-center flex-wrap">
        <li v-for="value in awardPoints" :key="value"
            class="point-scoring-item flex items-center justify-center flex-col"
        >
          <div class="point-scoring-icon-box">
            <award-icon />
          </div>
          <div class="point-scoring-content-box">
            <p class="point-scoring-value">
              {{ value.toLocaleString() }}
            </p>
            <p class="point-scoring-label">
              Steps
            </p>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import AwardIcon from "@/svgs/award-icon";

export default {
    name: "WearableModalFour",
    components: {AwardIcon},
    data() {
        return {
            awardPoints: [1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000]
        };
    },
    methods: {
        closeParentModal() {
            this.$emit("closeModal");
        }
    }
};
</script>

<style scoped>

</style>