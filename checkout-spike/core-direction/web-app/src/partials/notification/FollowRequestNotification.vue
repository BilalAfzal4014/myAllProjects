<template>
  <div class="flex">
    <div class="notification-img-container rounded-full">
      <img :src="imageUrl" alt="" class="rounded-full" @click="navigateToProfile">
    </div>
    <div class="notification-content-outer-box">
      <p class="notification-card-heading">
        {{ notificationDetail.notification_data.title }}
      </p>
      <p class="notification-content-description">
        {{ notificationDetail.notification_data.description }}
      </p>
      <div v-if="showFollowerButton" class="notification-btn-box flex items-center">
        <CustomButton v-if="!notificationDetail.isFriend" class="btn-accept" text="Follow Back"
                      @click="followBack(notificationDetail.sender_id)"
        />
        <CustomButton class="btn-accept" text="View Profile" @click="navigateToProfile" />
      </div>
      <div v-else-if="showButtons" class="notification-btn-box flex items-center">
        <CustomButton class="btn-accept" text="Accept Request"
                      @click="
                        () => {
                          updateUserFollowingStatus(ACCEPT);
                          updateNotificationMessage(ACCEPT);
                        }
                      "
        />
        <CustomButton class="btn-decline text-white" text="Decline Request"
                      @click="
                        () => {
                          updateUserFollowingStatus(REJECT);
                          updateNotificationMessage(REJECT);
                        }
                      "
        />
      </div>
      <div v-else>
        <CustomButton class="btn-accept mr-2" text="View Profile" @click="navigateToProfile" />
      </div>
    </div>
  </div>
</template>

<script>
import DefaultImage from "../../assets/images/default_profile_img.png";
import {FOLLOW_REQUEST_MESSAGES, FOLLOW_REQUEST_STATUS} from "@/common/constants/constants";
import {updateNotificationMessage} from "@/apiManager/notification";
import {sendFollowRequest, updateFollowingStatus} from "@/apiManager/user";
import toastr from "toastr";
import CustomButton from "@/components/form/custom-button";

export default {
    name: "FollowRequestNotification",
    components: {CustomButton},
    data() {
        return {
            ACCEPT: FOLLOW_REQUEST_STATUS.ACCEPTED,
            REJECT: FOLLOW_REQUEST_STATUS.REJECTED,
            acceptMessage: FOLLOW_REQUEST_MESSAGES.ACCEPTED,
            rejectMessage: FOLLOW_REQUEST_MESSAGES.REJECTED,
            followMessage: FOLLOW_REQUEST_MESSAGES.FOLLOWING,
            followerMessage: FOLLOW_REQUEST_MESSAGES.FOLLOWER
        };
    },
    props: {
        notificationDetail: {
            type: Object,
            default: null,
        },
    },
    computed: {
        imageUrl() {
            return this.notificationDetail.sender.profile_picture
                ? this.constants.getImageUrl(this.notificationDetail.sender.profile_picture)
                : DefaultImage;
        },
        showButtons() {
            const title = this.notificationDetail.notification_data.title;
            return ![this.acceptMessage, this.rejectMessage, this.followMessage, this.followerMessage].includes(title);
        },
        showFollowerButton() {
            return this.notificationDetail.notification_data.title === this.followerMessage && !this.notificationDetail.sender.isFriend && this.notificationDetail.sender.status === "unknown" && this.notificationDetail.sender.friendship !== "";
        }
    },
    methods: {
        getUpdateNotificationMessagePayload(status) {
            this.title =
          status === this.ACCEPT ? this.acceptMessage : this.rejectMessage;
            this.description =
          status === this.ACCEPT
              ? `You have accepted friend request of ${this.notificationDetail.sender.firstname} ${this.notificationDetail.sender.lastname}`
              : `You declined ${this.notificationDetail.sender.firstname} ${this.notificationDetail.sender.lastname}'s friend request`;
            return {
                notificationId: this.notificationDetail.id,
                title: this.title,
                description: this.description,
            };
        },
        getUpdateFollowingStatusPayload(status) {
            return {
                followerId: this.notificationDetail.sender_id,
                status: status,
            };
        },
        updateNotificationMessage(status) {
            updateNotificationMessage(
                this.getUpdateNotificationMessagePayload(status)
            )
                .then((response) => {
                    this.notificationDetail.notification_data.title = response.data.title;
                    this.notificationDetail.notification_data.description =
                response.data.description;
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        },
        updateUserFollowingStatus(status) {
            updateFollowingStatus(this.getUpdateFollowingStatusPayload(status))
                .then((response) => {
                    toastr.success(response.message);
                    this.updateNotificationMessage(status);
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        },
        navigateToProfile() {
            this.$router.push("/profile/" + this.notificationDetail.sender.username);
        },
        followBack(userId) {
            sendFollowRequest({followingId: userId})
                .then(() => {
                    this.notificationDetail.sender.isFriend = true;
                    toastr.success("You have followed back successfully");
                })
                .catch((error) => toastr.error(error));
        }
    },
};
</script>

<style scoped>
.notification-img-container img {
  min-width: 50px;
  max-width: 50px;
  height: 50px;
  margin-right: 15px;
}

.notification-card-heading {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 5px;
}

.notification-content-description {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0;
  text-align: left;
  margin-bottom: 16px;
}

.notification-btn-box {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
}

.btn-accept {
  padding: 8px 20px;
  color: #f2f5ea;
  background-color: #690fad;
  border-radius: 10px;
}

.btn-decline {
  padding: 8px 20px;
  color: #690fad;
  background-color: #f2f5ea;
  border-radius: 10px;
}
</style>