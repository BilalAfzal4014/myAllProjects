<template>
  <div>
    <button @click="showModal = !showModal">
      <img
        :src="`${getImageURL(friend.profile_picture)}`"
        :alt="`${friend.firstname} ${friend.lastname} Profile Image`"
        class="friend-img"
      >
      <span class="profile-status">
        {{ friend.privacy }}
      </span>
      <span class="friend-name">
        {{ friend.firstname }} {{ friend.lastname }}
      </span>
    </button>
    <FriendModal v-if="showModal" :user="friend" @closeFriendModal="closeFriendModal" />
  </div>
</template>

<script>
import FriendModal from "@/partials/group/FriendModal";
import constants from "@/constants/constants";
import DefaultImage from "@/assets/images/default_profile_img.png";

export default {
  name: "FriendCard",
  components: {FriendModal},
  props: {
    friend: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      showModal: false
    };
  },
  methods: {
    getImageURL(imageName) {
      return imageName ? constants.getImageUrl(imageName) : DefaultImage;
    },
    showUserDetailModal() {
      this.showModal = true;
    },
    closeFriendModal() {
      this.showModal = false;
    }
  }
};
</script>

<style scoped>
.friend-card {
  cursor: pointer;
  padding: 10px;
  background-color: #ffffff;
  border-radius: 11px;
  -webkit-box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0 22px 40px rgba(0, 0, 0, 0.1);
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-box-pack: start;
  -ms-flex-pack: start;
  justify-content: flex-start;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  width: 100%;
  max-width: 186px;
  height: 248px;
}

@media screen and (max-width: 500px) {
  .friend-card {
    max-width: 100%;
  }
}

.friend-card .friend-img {
  width: 130px;
  height: 130px;
  margin: 15px auto 13px;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
  border-radius: 50%;
}

@media screen and (max-width: 767px) {
  .friend-card .friend-img {
    width: 117px;
    height: 117px;
  }
}

.friend-card .profile-status {
  padding: 4px;
  width: 67px;
  color: #ffffff;
  background: #690fad;
  border-radius: 4.50262px;
  -webkit-border-radius: 4.50262px;
  -moz-border-radius: 4.50262px;
  -ms-border-radius: 4.50262px;
  -o-border-radius: 4.50262px;
  font-family: "Montserrat", sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0;
  text-align: center;
  display: inline-block;
  margin-bottom: 16px !important;
}

.friend-card .friend-name {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 500;
  line-height: 15px;
  letter-spacing: 0;
  text-align: center;
  display: block;
}
</style>