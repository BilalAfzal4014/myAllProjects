<template>
  <div id="friend-modal" class="custom-modal m-auto overflow-y-auto">
    <unfollow-user-confirmation v-if="isShowUserConfirmation" :username="user.username" @unfollowUser="unfollowUser" />
    <div class="modal-center">
      <div class="modal-outer-box">
        <div class="modal-inner-box">
          <div class="modal-header">
            <div class="btn-modal-close ml-auto" @click="closeFriendModal">
              <close-icon />
            </div>
          </div>
          <div class="modal-body">
            <div class="friend-popup-box">
              <PreviewImage
                :src="user.profile_picture"
                :useSrcset="false"
                alt="Profile Image"
                class="friend-profile-img"
                type="logo"
              />
              <div class="friend-profile-info-box">
                <p class="friend-name">
                  {{ fullName }}
                </p>
                <p class="friend-username">
                  @{{ user.username }}
                </p>
                <p class="friend-title">
                  Interests
                </p>
                <ul class="interested-activity-list flex-wrap">
                  <li
                    v-for="activity in activities"
                    :key="`activity-type-icon-${activity.name}`"
                    class="interested-activity-item"
                  >
                    <img
                      :alt="`${activity.imageName}`"
                      :src="`${getActivityTypeUrl(activity.imageName)}`"
                      class="activity-icon"
                    >
                  </li>
                </ul>
                <div class="modal-btn">
                  <button class="btn-view-profile" @click="viewProfile(user)">
                    View Profile
                  </button>
                  <button
                    v-if="notOwnProfile"
                    :class="buttonClass"
                    :disabled="isRequested"
                    @click="inviteFriend(user.status, user.friendship, user.isFriend)"
                  >
                    {{ buttonLabel }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import constants from "@/constants/constants";
import {PRIVACY_TYPE} from "@/common/constants/constants";
import * as toastr from "toastr";
import {sendFollowRequest, sendUnfollowRequest} from "@/apiManager/user";
import UnfollowUserConfirmation from "@/components/social/unfollow-user-confirmation";
import CloseIcon from "@/svgs/close-icon";
import followStatusMixin from "@/mixin/followStatusMixin";
import PreviewImage from "@/components/PreviewImage";

export default {
  name: "FriendModal",
  components: {PreviewImage, CloseIcon, UnfollowUserConfirmation},
  props: {
    user: {
      type: Object,
      default: null,
    },
    challengeDetail: {
      type: Boolean,
      default: false,
    }

  },
  mixins: [followStatusMixin],
  computed: {
    fullName() {
      return `${this.user.firstname} ${this.user.lastname}`;
    },
    loggedInUserProfile() {
      return this.$store.getters.getStoreUserProfileGetters();
    },
    notOwnProfile() {
      return this.user.id !== this.loggedInUserProfile.id;
    },
    isRequested() {
      return this.user.status === "requested";
    },
    isFriend() {
      return this.user.isFriend;
    },
    buttonClass() {
      return "btn-view-profile";
    },
    activities() {
      return this.user.hasOwnProperty("user_interested_activities")
        ? this.user.user_interested_activities
        : this.user.activity_types;
    },
  },
  data() {
    return {
      privacyTypes: PRIVACY_TYPE,
      isShowUserConfirmation: false
    };
  },
  methods: {
    viewProfile(user) {
      if (this.isPublicProfileRoute()) {
        this.closeFriendModal();
      }

      if (this.isViewingOwnProfile(user)) {
        this.navigateToCommunity();
      } else {
        this.navigateToProfileBasedOnPrivacy(user);
      }


    },
    isViewingOwnProfile(user) {
      return user.id === this.loggedInUserProfile.id;
    },

    navigateToCommunity() {
      this.$router.push("/community");
    },

    navigateToProfileBasedOnPrivacy(user) {
      const isProfilePublicOrFriend = user.privacy === this.privacyTypes.PUBLIC || user.isFriend;
      const route = isProfilePublicOrFriend ? `/profile/${user.username}` : `/private/${user.username}`;
      this.$router.push(route);
    },

    isPublicProfileRoute() {
      return this.$route.name === "PublicProfile";
    },
    closeFriendModal() {
      this.$emit("closeFriendModal");
    },

    getActivityTypeUrl: function (imageName) {
      return constants.getImageUrl("activity/" + imageName);
    },
    getSendFollowRequestPayload() {
      return {
        followingId: this.user.id,
      };
    },
    inviteFriend(status, friendship, isFriend) {
      if (status === "unknown" || (friendship === "follower" && !isFriend)) {
        this.sendFollowRequest();
        return;
      }
      this.isShowUserConfirmation = true;
      return false;
    },
    async unfollowUser() {
      try {
        await sendUnfollowRequest(this.user.id);
        toastr.success("unfollowed user");
        this.$emit("updateUserObject", {
          type: "unfollow",
          id: this.user.id
        });
        this.isShowUserConfirmation = false;
      } catch (error) {
        toastr.error(this.getErrorMessage(error));
      }
    },
    async sendFollowRequest() {
      try {
        await sendFollowRequest(this.getSendFollowRequestPayload());
        this.updateFriendshipStatusBasedOnPrivacy(this.user);
        toastr.success("Invite is sent");
      } catch (error) {
        toastr.error(this.getErrorMessage(error));
      }
    },
    updateFriendshipStatusBasedOnPrivacy() {
      this.$emit("updateUserObject", {
        type: "follow",
        id: this.user.id
      });
    },
    getErrorMessage(error) {
      return error[0]?.response?.data?.errors[0]?.error || "An unexpected error occurred";
    }
  },
};
</script>

<style scoped>
.modal-header {
  padding: 0 !important;
}

.custom-modal .modal-header {
  padding: 0 !important;
}

.modal-btn {
  display: flex;
  flex-wrap: wrap;
}

@media (max-width: 767px) {
  .modal-btn {
    flex-wrap: wrap;
  }

  .modal-btn a {
    margin: auto;
  }
}

.custom-modal {
  position: fixed;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  z-index: 9999;
  padding: 1rem 0.5rem;
  background: #0000002d;
  overflow-y: auto;
  width: 100%;
  min-height: 100vh;
  height: 100%;
}

@media (max-width: 767px) {
  .custom-modal {
    padding: 10vh 0.5rem;
  }
}

#friend-modal .modal-outer-box {
  max-width: 550px;
  background: #ffffff;
  -webkit-box-shadow: 0 22px 40px 0 #0000001a;
  box-shadow: 0 22px 40px 0 #0000001a;
  padding: 33px 30px 30px;
  border-radius: 11px;
  -webkit-border-radius: 11px;
  -moz-border-radius: 11px;
  -ms-border-radius: 11px;
  -o-border-radius: 11px;
}

@media screen and (max-width: 767px) {
  #friend-modal .modal-outer-box {
    max-width: 260px;
    border-radius: 14px;
    -webkit-border-radius: 14px;
    -moz-border-radius: 14px;
    -ms-border-radius: 14px;
    -o-border-radius: 14px;
  }
}

#friend-modal .friend-popup-box {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: flex-start;
  -ms-flex-align: flex-start;
  align-items: flex-start;
  -webkit-column-gap: 25px;
  column-gap: 25px;
}

@media screen and (max-width: 767px) {
  #friend-modal .friend-popup-box {
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    row-gap: 20px;
  }

  #friend-modal .btn-view-profile {
    display: block;
    margin: auto !important;
  }
}

#friend-modal .friend-profile-img {
  min-width: 150px;
  max-width: 150px;
  min-height: 150px;
  max-height: 150px;
  -o-object-fit: fill;
  object-fit: fill;
  -o-object-position: center;
  object-position: center;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

@media screen and (max-width: 767px) {
  #friend-modal .friend-profile-img {
    min-width: 161px;
    max-width: 161px;
    min-height: 161px;
    max-height: 161px;
  }
}

#friend-modal .friend-name {
  font-family: "Montserrat", sans-serif;
  font-size: 18px;
  font-weight: 600;
  line-height: 22px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 5px;
}

@media screen and (max-width: 767px) {
  #friend-modal .friend-name {
    text-align: center;
  }
}

#friend-modal .friend-username {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 10px;
}

@media screen and (max-width: 767px) {
  #friend-modal .friend-username {
    text-align: center;
    margin-bottom: 12px;
  }
}

#friend-modal .friend-title {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 700;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 9px;
}

@media screen and (max-width: 767px) {
  #friend-modal .friend-title {
    margin-bottom: 14px;
    font-size: 14px;
    line-height: 17px;
    text-align: center;
  }
}

#friend-modal .interested-activity-list {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-column-gap: 15px;
  column-gap: 15px;
  margin-bottom: 14px;
}

@media screen and (max-width: 767px) {
  #friend-modal .interested-activity-list {
    -webkit-column-gap: 20px;
    column-gap: 20px;
    margin-bottom: 20px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}

#friend-modal .activity-icon {
  width: 100%;
  max-width: 18px;
  -o-object-fit: contain;
  object-fit: contain;
  -o-object-position: center;
  object-position: center;
}

#friend-modal .btn-box {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
}

@media screen and (max-width: 767px) {
  #friend-modal .btn-box {
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}

#friend-modal .btn-view-profile {
  display: block;
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 600;
  line-height: 20px;
  letter-spacing: 0;
  text-align: center;
  padding: 11px;
  border-radius: 30px;
  -webkit-border-radius: 30px;
  -moz-border-radius: 30px;
  -ms-border-radius: 30px;
  -o-border-radius: 30px;
  background-color: #690fad;
  color: #ffffff;
  min-width: 144px;
  max-width: 144px;
  margin: 0 10px 10px 0;
}

@media screen and (max-width: 767px) {
  #friend-modal .btn-view-profile {
    padding: 14px 28px;
    font-size: 15px;
    line-height: 19px;
    text-align: center;
    min-width: 165px;
    margin-bottom: 10px !important;
  }
}

#friend-modal .modal-outer-box{
  position: relative;
}
#friend-modal .modal-outer-box .modal-header{
  position: absolute;
  top: 1rem;
  right: 1rem;
}
@media (min-width:768px) {
  #friend-modal .btn-view-profile{
    margin-bottom: 0 !important;
  }
  #friend-modal .friend-name{
    max-width: 90%;
  }
}
</style>