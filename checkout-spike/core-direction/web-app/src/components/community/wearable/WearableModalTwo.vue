<template>
  <div class="modal-body px-5">
    <div class="form-container mx-auto">
      <div class="activity-box flex items-center justify-center flex-col">
        <div class="activity-icon-box">
          <img alt="" class="activity-icon" src="/assets/images/wearable/activity-icon-01.png">
        </div>
        <div class="activity-info-box">
          <p class="activity-name">
            Activity Log
          </p>
          <p class="activity-points">
            7 Points Max
          </p>
          <p class="activity-duration">
            Per Day
          </p>
        </div>
      </div>
      <div class="point-scoring-box">
        <p class="point-scoring-title">
          4 Core Points
        </p>
        <p class="point-scoring-desc">
          Create an activity & add to your calendar.
        </p>
        <ul class="point-scoring-list flex items-center justify-center flex-wrap">
          <li v-for="point in scoringPointsFour" :key="'three-scoring-points-' + point"
              class="point-scoring-item active"
          >
            <core-points-icon />
          </li>
        </ul>
      </div>
      <div class="point-scoring-box point-scoring-step-list">
        <p class="point-scoring-title">
          +3 Core Points
        </p>
        <p class="point-scoring-desc">
          Share with a friend who accepts the invite.
        </p>
        <ul class="point-scoring-list flex items-center justify-center flex-wrap">
          <li v-for="point in scoringPointsThree" :key="'four-scoring-points-' + point"
              class="point-scoring-item active"
          >
            <core-points-icon />
          </li>
        </ul>
        <p class="point-scoring-title notice">
          +7 Accept a friends activity invite
        </p>
      </div>
    </div>
  </div>
</template>

<script>

import CorePointsIcon from "@/svgs/community/core-points-icon";

export default {
  name: "WearableModalTwo",
  components: {CorePointsIcon},
  data() {
    return {
      scoringPointsThree: 3,
      scoringPointsFour: 4,
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
#wearable-modal .point-scoring-step-list .notice {
  margin-top: clamp(10px, 2.604vh, 20px);
}
</style>