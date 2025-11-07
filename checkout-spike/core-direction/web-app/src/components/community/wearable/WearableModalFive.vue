<template>
  <div class="modal-body px-5">
    <div class="form-container mx-auto">
      <div class="activity-box flex items-center justify-center flex-col">
        <div class="activity-icon-box">
          <img alt="" class="activity-icon" src="/assets/images/wearable/activity-icon-04.png">
        </div>
        <div class="activity-info-box">
          <p class="activity-name">
            Active Heart Rate
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
          EVERY SECOND COUNTS
        </p>
        <p class="wearable-desc">
          +1 point for every 3 consecutive minutes your heart rate is in an active heart rate zone
        </p>
      </div>
      <ul class="point-scoring-list point-scoring-step-list flex items-center justify-center flex-wrap">
        <li v-for="(item, index) in pointScoringItems"
            :key="index" class="point-scoring-item flex items-center justify-center flex-col"
        >
          <div class="point-scoring-icon-box">
            <award-icon />
          </div>
          <div class="point-scoring-content-box">
            <p class="point-scoring-value">
              {{ item.value }} Min
            </p>
            <p class="point-scoring-label">
              +{{ item.label }}
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
    name: "WearableModalFive",
    components: {AwardIcon},
    data() {
        return {
            pointScoringItems: [
                {value: 3, label: 1},
                {value: 6, label: 2},
                {value: 9, label: 3},
                {value: 12, label: 4},
                {value: 15, label: 5},
                {value: 18, label: 6},
                {value: 21, label: 7},
                {value: 24, label: 8},
                {value: 27, label: 9},
                {value: 30, label: 10},
            ]
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