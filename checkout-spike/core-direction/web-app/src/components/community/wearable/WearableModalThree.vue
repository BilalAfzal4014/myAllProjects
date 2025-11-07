<template>
  <div class="modal-body px-5">
    <div class="form-container mx-auto">
      <div class="activity-box flex items-center justify-center flex-col">
        <div class="activity-icon-box">
          <img alt="" class="activity-icon" src="/assets/images/wearable/activity-icon-02.png">
        </div>
        <div class="activity-info-box">
          <p class="activity-name">
            Bookings
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
        <p class="point-scoring-title">
          7 Core Points
        </p>
        <p class="point-scoring-desc">
          Booking an activity will award 7 Core Points
        </p>
        <ul class="point-scoring-list flex items-center justify-center flex-wrap">
          <li v-for="point in scoringPointsSeven" :key="'seven-scoring-points-' + point"
              class="point-scoring-item active"
          >
            <core-points-icon />
          </li>
        </ul>
      </div>
      <div class="point-scoring-box">
        <p class="point-scoring-title">
          +3 CORE POINTS
        </p>
        <p class="point-scoring-desc">
          A booked activity check-in will award +3 Core Points
        </p>
        <ul class="point-scoring-list flex items-center justify-center flex-wrap">
          <li v-for="point in scoringPointsThree" :key="'three-scoring-points-' + point"
              class="point-scoring-item active"
          >
            <core-points-icon />
          </li>
        </ul>
      </div>
      <div class="point-scoring-box">
        <p class="point-scoring-title">
          Note
        </p>
        <p class="point-scoring-desc">
          An activity Check-in is done by the activity partner location so ensure
          that you ask to be checked in when you arrive at your activity.
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
            scoringPointsSeven: 7,
            scoringPointsThree: 3,
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