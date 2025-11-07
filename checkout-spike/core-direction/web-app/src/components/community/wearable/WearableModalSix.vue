<template>
  <div class="modal-body px-5">
    <div class="form-container mx-auto">
      <div class="activity-box flex items-center justify-center flex-col">
        <div class="activity-icon-box">
          <img alt="" class="activity-icon" src="/assets/images/wearable/activity-icon-04.png">
        </div>
        <div class="activity-info-box">
          <p class="activity-name">
            On-Demand
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
          3 Core Points
        </p>
        <p class="point-scoring-desc">
          Receive 3 Core Points when you press play on any video or audio content from our on-demand library.
        </p>
        <ul class="point-scoring-list flex items-center justify-center flex-wrap">
          <li v-for="icon in awardIcons" :key="icon" class="point-scoring-item">
            <award-icon />
          </li>
        </ul>
      </div>
      <div class="point-scoring-box">
        <p class="point-scoring-title">
          Note
        </p>
        <p class="point-scoring-desc">
          Knowledge is power and while we encourage you to consume as many content pieces as possible, you can only earn
          a maximum of 3 points per day for this category.
        </p>
      </div>
      <div class="wearable-btn-box mx-auto flex justify-center flex-col btn-connect-wearable-box">
        <button class="btn-warable-action btn-connect-wearable rounded-full active" @click="connectWearable">
          Connect Wearable
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import AwardIcon from "@/svgs/award-icon";

export default {
    name: "WearableModalSix",
    components: {AwardIcon},
    data() {
        return {
            awardIcons: 3
        };
    },
    methods: {
        closeParentModal() {
            this.$emit("closeModal");
        },
        connectWearable() {
            this.$emit("showModal", 7);
        }
    }
};
</script>

<style scoped>

</style>