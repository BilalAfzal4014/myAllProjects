<template>
  <div>
    <div @click="showUserDetailModal">
      <button class="friend-card">
        <img :src="`${userImage}`" :alt="`${user.firstname} ${user.lastname} Profile Image`" class="friend-img">
        <span class="profile-status">
          {{ user.isFriend ? "Following" : user.privacy }}
        </span>
        <span class="friend-name">
          {{ user.firstname }} {{ user.lastname }}
        </span>
      </button>
    </div>
    <FriendModal v-if="showModal" :user="user" @closeFriendModal="closeFriendModal"
                 @updateUserObject="event => updateUserObject(event)"
    />
  </div>
</template>

<script>
import constants from "@/constants/constants";
import DefaultImage from "../../assets/images/default_profile_img.png";
import FriendModal from "@/partials/group/FriendModal";

export default {
  name: "ParticipantCard",
  components: {FriendModal},
  props: {
    user: {
      type: Object,
      default: null
    }
  },
  data() {
    return {
      userImage: this.user.profile_picture ? constants.getImageUrl(this.user.profile_picture) + "?optimizer=image&format=webp&width=200&aspect_ratio=1:1&sharpen=true" : DefaultImage,
      showModal: false
    };
  },
  methods: {
    showUserDetailModal() {
      this.showModal = true;
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
.profile-status {
  text-transform: capitalize;
}

.friend-card {
  cursor: pointer;
  padding: 10px;
  background-color: #FFFFFF;
  border-radius: 10px;
  -webkit-border-radius: 10px;
  -moz-border-radius: 10px;
  -ms-border-radius: 10px;
  -o-border-radius: 10px;
  -webkit-box-shadow: 0px 3.71106px 3.71106px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 21.7045px 39.4626px rgba(0, 0, 0, 0.1);
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
  height: 225px;

}

@media screen and (max-width: 500px) {
  .friend-card {
    max-width: 100%;
  }
}

#friends .btn-friend-card {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  height: 139px;
  padding: 0px 5px 5px;
}

.friend-card .friend-img {
  width: 100px;
  height: 100px;
  margin: 20px auto 19px;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
  border-radius: 50%;
  box-shadow: 0px 3.9462637901306152px 3.9462637901306152px 0px #00000040;
}

@media screen and (max-width: 767px) {
  .friend-card .friend-img {
    width: 94px;
    height: 94px;
    margin: 16px auto 17px;
  }
}

.friend-card .friend-name {
  font-family: 'Montserrat', sans-serif;
  font-size: 13px;
  font-weight: 500;
  line-height: 25px;
  letter-spacing: 0em;
  text-align: center;
}

#group-profile .friend-card {
  -webkit-box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 22px 40px rgba(0, 0, 0, 0.1);
  border-radius: 11px;
  height: 248px;
}

#group-profile .friend-img {
  width: 130px;
  height: 130px;
  margin-top: 15px;
  margin-bottom: 17px;
}

@media screen and (max-width: 767px) {
  #group-profile .friend-img {
    width: 117px;
    height: 117px;
  }
}

.profile-status {
  padding: 4px;
  width: 67px;
  color: #FFFFFF;
  background: #690FAD;
  border-radius: 4.50262px;
  -webkit-border-radius: 4.50262px;
  -moz-border-radius: 4.50262px;
  -ms-border-radius: 4.50262px;
  -o-border-radius: 4.50262px;
  font-family: 'Montserrat', sans-serif;
  font-size: 10px;
  font-weight: 500;
  line-height: 12px;
  letter-spacing: 0em;
  text-align: center;
  margin-bottom: 10px;
}


</style>