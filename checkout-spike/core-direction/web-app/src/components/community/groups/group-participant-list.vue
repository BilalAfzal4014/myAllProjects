<template>
  <ul class="participants-list flex items-center flex-wrap justify-end">
    <li v-for="(friend, index) in friends" :key="index" class="participants-item">
      <img :src="`${getFriendImage(friend.profile_picture)}`" alt="Participant image" class="participants-img rounded-full">
    </li>
  </ul>
</template>

<script>
import constants from "@/constants/constants";
import DefaultImage from "../../../assets/images/default_profile_img.png";

export default {
  name: "GroupParticipantList",
  props: {
    friends: {
      type: Array,
      default: () => [],
    }
  }, methods: {
    getFriendImage(imageUrl) {
      return imageUrl ? constants.getImageUrl(imageUrl) + "?optimizer=image&format=webp&width=92&aspect_ratio=1:1&sharpen=true" : DefaultImage;
    }
  }
};
</script>

<style scoped>

</style>