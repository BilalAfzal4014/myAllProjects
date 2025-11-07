<template>
  <ul class="activity-list flex items-start">
    <li v-for="(activityType, index) in activityTypes" :key="createActivityTypeKey(index, activityType.id)"
        class="activity-item"
    >
      <img :src="`${getActivityTypeImageUrl(activityType.imageName)}`" alt="Activity Type" class="activity-img">
    </li>
  </ul>
</template>

<script>
import constants from "@/constants/constants";

export default {
    name: "GroupActivityList",
    props: {
        activityTypes: {
            type: Array,
            default: () => [],
        }
    },
    methods: {
        createActivityTypeKey(index, id) {
            return `${index}-group-friend-${id}`;
        },
        getActivityTypeImageUrl(imageName) {
            return constants.getImageUrl("activity/" + imageName);
        }
    }
};
</script>

<style scoped>

</style>