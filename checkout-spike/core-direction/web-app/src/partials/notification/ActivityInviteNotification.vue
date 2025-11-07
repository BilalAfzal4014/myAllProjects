<template>
  <div class="flex items-start">
    <div class="img-box activity-img-box">
      <img :src="`${imageUrl}`" alt="" class="notification-img">
      <span class="notification-icon-box">
        <img :src="`${imageUrl}`" alt="" class="notification-icon">
      </span>
    </div>
    <div class="content-box">
      <p class="notification-card-title">
        {{ notificationDetail.notification_data.title }}
      </p>
      <p class="notification-card-desc">
        {{ notificationDetail.notification_data.description }}
      </p>
      <div
        v-if="isActivityRequest"
        class="notification-btn-box flex items-center"
      >
        <button
          class="btn-accept-group btn-group"
          @click="
            () => {
              updateActivityInviteStatus(ACCEPT);
              updateNotificationMessage(ACCEPT);
            }
          "
        >
          Accept
        </button>
        <button
          class="btn-decline-group btn-group"
          @click="
            () => {
              updateActivityInviteStatus(REJECT);
              updateNotificationMessage(REJECT);
            }
          "
        >
          Decline
        </button>
      </div>
      <div v-else>
        <button
          class="btn-accept-group btn-group"
          @click="navigateToCommunity(notificationDetail)"
        >
          View Details
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import DefaultImage from "../../assets/images/default_profile_img.png";
import {ACTIVITY_INVITE_MESSAGES, FOLLOW_REQUEST_STATUS, NOTIFICATION_TYPE,} from "@/common/constants/constants";
import {dashDateFormat, TimeFormatAMPM} from "@/utils";
import {updateNotificationMessage} from "@/apiManager/notification";
import * as toastr from "toastr";
import {updateActivityInvite} from "@/apiManager/activities";
import notificationMixin from "@/mixin/notificationMixin";

export default {
  name: "ActivityInviteNotification",
  props: {
    notificationDetail: {
      type: Object,
      default: null,
    },
  },
  mixins: [notificationMixin],
  data() {
    return {
      activityRequest: NOTIFICATION_TYPE.NEW_ACTIVITY_INVITE,
      ACCEPT: FOLLOW_REQUEST_STATUS.ACCEPTED,
      REJECT: FOLLOW_REQUEST_STATUS.REJECTED,
      acceptMessage: ACTIVITY_INVITE_MESSAGES.ACCEPTED,
      rejectMessage: ACTIVITY_INVITE_MESSAGES.REJECTED,
      activityTypeImage: this.constants.getImageUrl(
        this.notificationDetail.data.activity_type_image
      ),
      title: "",
      description: "",
    };
  },
  computed: {
    imageUrl() {
      if (this.notificationDetail.sender.profile_picture) {
        return this.constants.getImageUrl(this.notificationDetail.sender.profile_picture);
      }
      return DefaultImage;
    },
    isActivityRequest() {
      return this.notificationDetail.notification_data.title === this.activityRequest;
    }
  },
  methods: {
    getUpdateNotificationMessagePayload(status) {
      this.title =
          status === this.ACCEPT
            ? `${this.acceptMessage} ${this.notificationDetail.sender.firstname} ${this.notificationDetail.sender.lastname}`
            : `${this.rejectMessage} ${this.notificationDetail.sender.firstname} ${this.notificationDetail.sender.lastname}`;
      this.description =
          status === this.ACCEPT
            ? `You have accepted the invitation from ${
              this.notificationDetail.sender.firstname
            } ${this.notificationDetail.sender.lastname} of ${
              this.notificationDetail.data.type
            } activity on ${dashDateFormat(
              this.notificationDetail.data.date
            )} at ${TimeFormatAMPM(
              new Date(this.notificationDetail.data.time)
            )}`
            : `You have declined the invitation from ${
              this.notificationDetail.sender.firstname
            } ${this.notificationDetail.sender.lastname} of ${
              this.notificationDetail.data.type
            } activity on ${dashDateFormat(
              this.notificationDetail.data.date
            )} at ${TimeFormatAMPM(
              new Date(this.notificationDetail.data.time)
            )}`;

      return {
        notificationId: this.notificationDetail.id,
        title: this.title,
        description: this.description,
      };
    },
    getUpdateActivityInviteStatusPayload(status) {
      return {
        invitedUserId: this.notificationDetail.sender_id,
        userDiaryActivityId:
        this.notificationDetail.data.user_diary_activity_id,
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
    updateActivityInviteStatus(status) {
      updateActivityInvite(this.getUpdateActivityInviteStatusPayload(status))
        .then((response) => {
          toastr.success(response.data.message);
          this.updateNotificationMessage(status);
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
  },
};
</script>

<style scoped>
#app-header .notification-card {
  background: #ffffff;
  -webkit-box-shadow: 0px 2px 7px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 2px 7px rgba(0, 0, 0, 0.1);
  border-radius: 4px;
  padding: 14px 22px;
  margin-bottom: 10px;
}

@media (max-width: 767px) {
  #app-header .notification-card {
    padding-left: 15px;
    padding-right: 15px;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    -webkit-column-gap: 10px;
    column-gap: 10px;
    row-gap: 10px;
  }
}

#app-header .img-box {
  width: 100%;
  min-width: 50px;
  max-width: 50px;
  max-height: 50px;
  position: relative;
  margin-right: 15px;
}

#app-header .img-box .notification-img {
  width: 100%;
  max-width: 50px;
  max-height: 43px;
  height: 43px;
}

#app-header .activity-img-box {
  background: #f2f5ea;
  border: 1.5px solid #690fad;
  border-radius: 50%;
  padding: 2px;
}

#app-header .notification-img {
  width: 100%;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

#app-header .notification-icon {
  min-width: 20px;
  min-height: 20px;
  max-width: 20px;
  max-height: 20px;
  border-radius: 50%;
}

#app-header .notification-card-title {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 700;
  line-height: 20px;
  letter-spacing: 0px;
  text-align: left;
  margin-bottom: 5px;
}

#app-header .notification-card-desc {
  font-family: "Montserrat", sans-serif;
  font-size: 12px;
  font-weight: 400;
  line-height: 15px;
  letter-spacing: 0em;
  text-align: left;
  margin-bottom: 16px;
}

#app-header .notification-icon-box {
  height: 25px;
  width: 25px;
  position: absolute;
  right: -5px;
  bottom: -3px;
  padding: 3px;
  background-color: #ffffff;
  -webkit-box-shadow: 2px 2px 5px 0px #00000073;
  box-shadow: 2px 2px 5px 0px #00000073;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  -ms-border-radius: 50%;
  -o-border-radius: 50%;
}

#app-header .notification-card-desc strong {
  font-weight: 700;
}

#app-header .notification-btn-box {
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-column-gap: 10px;
  column-gap: 10px;
  row-gap: 10px;
}

#app-header .btn-group {
  cursor: pointer;
  border-radius: 8px;
  -webkit-border-radius: 8px;
  -moz-border-radius: 8px;
  -ms-border-radius: 8px;
  -o-border-radius: 8px;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0px;
  text-align: center;
}

#app-header .btn-visit-group {
  padding: 8px 16px;
  color: #f2f5ea;
  background-color: #690fad;
}

#app-header .btn-leave-group {
  padding: 8px 16px;
  color: #690fad;
  background-color: #f2f5ea;
}

#app-header .btn-view-detail {
  padding: 8px 35px;
  color: #f2f5ea;
  background-color: #690fad;
}

#app-header .btn-accept-group {
  padding: 8px 20px;
  color: #f2f5ea;
  background-color: #690fad;
}

#app-header .btn-decline-group {
  padding: 8px 20px;
  color: #690fad;
  background-color: #f2f5ea;
}

#app-header .notification-footer {
  padding: 15px 10px 16px;
  text-align: center;
}

#app-header .btn-all-notificatioon {
  padding: 8px 16px;
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0px;
  text-align: center;
  color: #690fad;
}
</style>