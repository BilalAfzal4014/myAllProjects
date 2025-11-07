<template>
  <div class="modal-body px-5">
    <div class="form-container mx-auto">
      <p class="wearable-title">
        Core Points Explained
      </p>
      <ul class="wearable-track-list flex items-center justify-center flex-wrap">
        <li v-for="item in trackItems" :key="item.id" class="wearable-track-item">
          <component :is="item.component" />
        </li>
      </ul>
      <p class="wearable-subtitle">
        Core Points
      </p>
      <p class="wearable-desc mx-auto">
        The highest points earned against one of five categories (Activity log, Booking Check-in, Steps, Heart
        Rate and On-demand content played) will contribute to your Core Points with a 10 point maximum.
      </p>
      <ul class="score-point-list flex items-center justify-center flex-wrap">
        <li v-for="activeCorePoint in activeCorePointsIcon" :key="`active-${activeCorePoint}`"
            class="score-point-item active"
        >
          <core-points-icon :active="true" />
        </li>
        <li v-for="inActiveCorePoint in inActiveCorePointsIcon" :key="`inactive-${inActiveCorePoint}`"
            class="score-point-item"
        >
          <core-points-icon :active="false" />
        </li>
      </ul>

      <div class="wearable-btn-box mx-auto flex justify-center flex-col">
        <button class="btn-warable-action btn-learn-more rounded-full" @click="openModal(2)">
          Learn more
        </button>
        <button class="btn-warable-action btn-connect-wearable rounded-full" @click="openModal(7)">
          Connect Wearable
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import CloseIcon from "@/svgs/close-icon";
import ActivityLog from "@/svgs/wearable-modal-icons/activity-log.vue";
import LocationPointer from "@/svgs/company/location-pointer.vue";
import StepsIcon from "@/svgs/wearable-modal-icons/steps-icon.vue";
import HeartIcon from "@/svgs/wearable-modal-icons/heart-icon.vue";
import OnDemandVideoIcon from "@/svgs/wearable-modal-icons/on-demand-video-icon.vue";
import CorePointsIcon from "@/svgs/community/core-points-icon";

export default {
    name: "WearableModalOne",
    components: {CorePointsIcon, OnDemandVideoIcon, HeartIcon, StepsIcon, LocationPointer, ActivityLog, CloseIcon},
    data() {
        return {
            activeCorePointsIcon: 6,
            inActiveCorePointsIcon: 4,
            trackItems: [
                {id: 1, component: ActivityLog},
                {id: 2, component: LocationPointer},
                {id: 3, component: StepsIcon},
                {id: 4, component: HeartIcon},
                {id: 5, component: OnDemandVideoIcon},
            ],
        };
    },
    methods: {
        closeParentModal() {
            this.$emit("closeModal");
        },
        openModal(modalNumber) {
            this.$emit("showModal", modalNumber);
        }
    }
};
</script>

<style scoped>

</style>