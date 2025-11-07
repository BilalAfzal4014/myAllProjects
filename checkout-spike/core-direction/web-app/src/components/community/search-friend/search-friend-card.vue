<template>
  <div>
    <button class="friend-card" @click="showModal = !showModal">
      <img :src="`${getFriendImageUrl(connectionDetail.profile_picture)}`" :alt="`${connectionDetail.firstname} ${connectionDetail.lastname}`" class="friend-img">
      <p class="friend-name">
        {{ connectionDetail.firstname }} {{ connectionDetail.lastname }}
      </p>
    </button>
    <FriendModal v-if="showModal" :user="connectionDetail" @closeFriendModal="closeFriendModal"
                 @updateUserObject="event => updateUserObject(event)"
    />
  </div>
</template>

<script>
import constants from "@/constants/constants";
import DefaultImage from "@/assets/images/default_profile_img.png";
import FriendModal from "@/partials/group/FriendModal";

export default {
    name: "SearchFriendCard",
    props: {
        connectionDetail: {
            type: Object,
            required: true
        }
    },
    components: {
        FriendModal
    },
    data() {
        return {
            showModal: false
        };
    },
    methods: {
        getFriendImageUrl(imageUrl) {
            return imageUrl ? constants.getImageUrl(imageUrl) : DefaultImage;
        },
        closeFriendModal() {
            this.showModal = false;
        },
        updateUserObject(event) {
            this.$emit("updateUserObject", event);
        }

    }
};
</script>

<style scoped>

</style>