<template>
  <div
    class="notification-card-body flex items-center justify-between flex-wrap"
  >
    <div class="notification-info-box flex">
      <div class="img-box">
        <img :src="`${imageUrl}`" alt="" class="notification-img">
      </div>
      <div class="info-box">
        <p class="notification-card-title">
          {{ notification.notification_data.title }}
        </p>
        <p class="notification-card-desc">
          {{ notification.notification_data.description }}
        </p>
      </div>
    </div>
    <div v-if="showFollowerButton" class="action-button-box flex items-center flex-wrap">
      <CustomButton v-if="!notification.isFriend" class="btn-action btn-primary" text="Follow Back"
                    @click="followBack(notification.sender_id)"
      />
      <CustomButton class="btn-action btn-secondary" text="View Profile" @click="navigateToProfile" />
    </div>
    <div v-else-if="showButtons" class="action-button-box flex items-center flex-wrap">
      <CustomButton class="btn-action btn-primary" text="Accept Request"
                    @click="
                      () => {
                        updateUserFollowingStatus(ACCEPT);
                        updateNotificationMessage(ACCEPT);
                      }
                    "
      />
      <CustomButton class="btn-action btn-secondary " text="Decline Request"
                    @click="
                      () => {
                        updateUserFollowingStatus(REJECT);
                        updateNotificationMessage(REJECT);
                      }
                    "
      />
    </div>
    <div v-else>
      <CustomButton class="btn-action btn-primary mr-2" text="View Profile" @click="navigateToProfile" />
    </div>
  </div>
</template>

<script>
import {updateNotificationMessage} from "@/apiManager/notification";
import * as toastr from "toastr";
import {sendFollowRequest, updateFollowingStatus} from "@/apiManager/user";
import {FOLLOW_REQUEST_MESSAGES, FOLLOW_REQUEST_STATUS,} from "@/common/constants/constants";
import DefaultImage from "../../assets/images/default_profile_img.png";
import CustomButton from "@/components/form/custom-button";

export default {
    name: "FollowRequest",
    components: {CustomButton},
    props: {
        notification: {
            type: Object,
            default: null,
        },
    },
    data() {
        return {
            ACCEPT: FOLLOW_REQUEST_STATUS.ACCEPTED,
            REJECT: FOLLOW_REQUEST_STATUS.REJECTED,
            acceptMessage: FOLLOW_REQUEST_MESSAGES.ACCEPTED,
            rejectMessage: FOLLOW_REQUEST_MESSAGES.REJECTED,
            followMessage: FOLLOW_REQUEST_MESSAGES.FOLLOWING,
            followerMessage: FOLLOW_REQUEST_MESSAGES.FOLLOWER,
            hideButton: false,
            title: "",
            description: "",
            imageUrl: this.notification.sender.profile_picture
                ? this.constants.getImageUrl(this.notification.sender.profile_picture)
                : DefaultImage,
        };
    },
    computed: {
        showButtons() {
            const title = this.notification.notification_data.title;
            return ![this.acceptMessage, this.rejectMessage, this.followMessage, this.followerMessage].includes(title);
        },
        showFollowerButton() {
            return this.notification.notification_data.title === this.followerMessage && !this.notification.sender.isFriend && this.notification.sender.status === "unknown" && this.notification.sender.friendship !== "";
        }
    },

    methods: {
        getUpdateNotificationMessagePayload(status) {
            this.title =
          status === this.ACCEPT ? this.acceptMessage : this.rejectMessage;
            this.description =
          status === this.ACCEPT
              ? `You have accepted friend request of ${this.notification.sender.firstname} ${this.notification.sender.lastname}`
              : `You declined ${this.notification.sender.firstname} ${this.notification.sender.lastname}'s friend request`;
            return {
                notificationId: this.notification.id,
                title: this.title,
                description: this.description,
            };
        },
        getUpdateFollowingStatusPayload(status) {
            return {
                followerId: this.notification.sender_id,
                status: status,
            };
        },
        updateNotificationMessage(status) {
            updateNotificationMessage(
                this.getUpdateNotificationMessagePayload(status)
            )
                .then((response) => {
                    this.notification.notification_data.title = response.data.title;
                    this.notification.notification_data.description =
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
            const {username} = this.notification.sender;
            this.$router.push(`/profile/${username}`);
        },

        handleSuccess() {
            this.notification.sender.isFriend = true;
            toastr.success("You have followed back successfully");
        },

        handleError(error) {
            toastr.error(error);
        },
        async followBack(userId) {
            try {
                await sendFollowRequest({followingId: userId});
                this.handleSuccess();
            } catch (error) {
                this.handleError(error);
            }
        }
    },
};
</script>

<style scoped>
#notification .notification-card-body {
  width: 100%;
  max-width: 1070px;
  margin-left: auto;
  margin-right: auto;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 2rem;
}

@media (max-width: 411px) {
  #notification .notification-card-body {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}

@media (min-width: 768px) {
  #notification .notification-info-box {
    width: 50%;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
  }
}

@media (max-width: 767px) {
  #notification .notification-info-box {
    -webkit-column-gap: 1rem;
    column-gap: 1rem;
    row-gap: 1rem;
  }
}

@media (max-width: 411px) {
  #notification .notification-info-box {
    row-gap: 2rem;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
  }
}

#notification .img-box {
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
  position: relative;
}

@media (min-width: 768px) {
  #notification .img-box {
    margin-right: 35px;
    width: 100%;
    max-width: 100px;
    height: 100%;
    max-height: 100px;
  }
}

@media (max-width: 767px) {
  #notification .img-box {
    width: 80%;
    max-width: 80px;
    height: 80%;
    max-height: 80px;
  }
}

@media (max-width: 411px) {
  #notification .img-box {
    width: 100%;
    max-width: 100px;
    height: 100%;
    max-height: 100px;
    margin: auto;
  }
}

#notification .notification-img {
  width: 100%;
  max-width: 100px;
  min-height: 100px;
  max-height: 100px;
  border-radius: 50%;
}

@media (max-width: 767px) {
  #notification .notification-img {
    min-width: 80px;
    max-width: 80px;
    min-height: 80px;
    max-height: 80px;
  }
}

@media (max-width: 411px) {
  #notification .notification-img {
    width: 100%;
    max-width: 100px;
    min-height: 100px;
    max-height: 100px;
  }
}

#notification .notification-icon-box {
  position: absolute;
  right: 0;
  bottom: 0;
  padding: 6px;
  background-color: #ffffff;
  -webkit-box-shadow: 2px 2px 5px 0px #00000073;
  box-shadow: 2px 2px 5px 0px #00000073;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

#notification .info-box {
  width: 100%;
  max-width: 306px;
}

#notification .notification-card-title {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 700;
  line-height: 22px;
  letter-spacing: 0px;
  text-align: left;
  margin-bottom: 4px;
}

@media (max-width: 411px) {
  #notification .notification-card-title {
    text-align: center;
    margin-bottom: 1rem;
  }
}

#notification .notification-card-desc {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0em;
  text-align: left;
}

#notification .notification-card-desc strong {
  font-weight: 700;
}

@media (max-width: 411px) {
  #notification .notification-card-desc {
    text-align: center;
  }
}

#notification .participants-list-box {
  width: 100%;
  max-width: 250px;
}

#notification .participants-desc {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 17px;
  letter-spacing: 0px;
  text-align: left;
  margin-bottom: 4px;
}

#notification .participants-item {
  margin-right: -5px;
}

#notification .participants-img {
  min-width: 26px;
  max-width: 26px;
  min-height: 26px;
  max-height: 26px;
}

#notification .action-button-box {
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
}

@media (max-width: 411px) {
  #notification .action-button-box {
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
  }
}

#notification .btn-action {
  padding: 15px;
  width: 158px;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0px;
  text-align: center;
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
}

#notification .btn-action svg {
  margin-right: 18px;
}

#notification .btn-primary {
  color: #f2f5ea;
  background-color: #690fad;
}

#notification .btn-secondary {
  color: #690fad;
  background-color: #caa8f5;
}

#notification .activity-img-box {
  background-color: #690fad;
  padding: 21px;
}

#notification .friend-activity-img-box {
  background-color: #f2f5ea;
  border: 1.5px solid #690fad;
  padding: 24px;
}

#notification .friend-activity-img-box .notification-icon-box {
  padding: 0;
  background: transparent;
  right: -10px;
  bottom: -4px;
  -webkit-box-shadow: unset;
  box-shadow: unset;
}

#notification .booking-img-box {
  background-color: #f2f5ea;
  padding: 22px;
}

#notification .booking-img-box .notification-img {
  max-width: 45px;
  margin: auto;
}
</style>