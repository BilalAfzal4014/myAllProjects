<template>
  <div
    class="notification-card-body flex items-center justify-between flex-wrap"
  >
    <div class="notification-info-box flex">
      <div class="img-box">
        <img :src="`${imageUrl}`" alt="" class="notification-img">
        <span class="notification-icon-box">
          <img :src="`${activityTypeImage}`" alt="" class="notification-icon">
        </span>
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
    <div
      v-if="isActivityRequest"
      class="action-button-box flex items-center flex-wrap"
    >
      <button
        class="btn-action btn-primary"
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
        class="btn-action btn-secondary"
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
        class="btn-action btn-primary"
        @click="navigateToCommunity(notification)"
      >
        View Details
      </button>
    </div>
  </div>
</template>
<script>
import DefaultImage from "@/assets/images/default_profile_img.png";
import {updateActivityInvite} from "@/apiManager/activities";
import * as toastr from "toastr";
import {ACTIVITY_INVITE_MESSAGES, FOLLOW_REQUEST_STATUS, NOTIFICATION_TYPE,} from "@/common/constants/constants";
import {updateNotificationMessage} from "@/apiManager/notification";
import {dashDateFormat, TimeFormatAMPM} from "@/utils";
import notificationMixin from "@/mixin/notificationMixin";

export default {
  name: "ActivityInvite",
  props: {
    notification: {
      type: Object,
      default: null,
    },
  },
  mixins: [notificationMixin],
  data() {
    return {
      title: this.notification.notification_data.title,
      description: this.notification.notification_data.description,
      ACCEPT: FOLLOW_REQUEST_STATUS.ACCEPTED,
      REJECT: FOLLOW_REQUEST_STATUS.REJECTED,
      acceptMessage: ACTIVITY_INVITE_MESSAGES.ACCEPTED,
      rejectMessage: ACTIVITY_INVITE_MESSAGES.REJECTED,
      activityTypeImage: DefaultImage,
      activityRequest: NOTIFICATION_TYPE.NEW_ACTIVITY_INVITE,
      // this.constants.getImageUrl(
      //       this.notification.data.activity_type_image
      //   ),
      imageUrl: this.notification.sender.profile_picture
        ? this.constants.getImageUrl(this.notification.sender.profile_picture)
        : DefaultImage,
    };
  },
  computed: {
    isActivityRequest() {
      return this.notification.notification_data.title === this.activityRequest;
    }
  },
  methods: {
    getUpdateNotificationMessagePayload(status) {
      this.title =
          status === this.ACCEPT
            ? `${this.acceptMessage} ${this.notification.sender.firstname} ${this.notification.sender.lastname}`
            : `${this.rejectMessage} ${this.notification.sender.firstname} ${this.notification.sender.lastname}`;
      this.description =
          status === this.ACCEPT
            ? `You have accepted the invitation from ${
              this.notification.sender.firstname
            } ${this.notification.sender.lastname} of ${
              this.notification.data.type
            } activity on ${dashDateFormat(
              this.notification.data.date
            )} at ${TimeFormatAMPM(new Date(this.notification.data.time))}`
            : `You have declined the invitation from ${
              this.notification.sender.firstname
            } ${this.notification.sender.lastname} of ${
              this.notification.data.type
            } activity on ${dashDateFormat(
              this.notification.data.date
            )} at ${TimeFormatAMPM(new Date(this.notification.data.time))}`;

      return {
        notificationId: this.notification.id,
        title: this.title,
        description: this.description,
      };
    },
    getUpdateActivityInviteStatusPayload(status) {
      return {
        invitedUserId: this.notification.sender_id,
        userDiaryActivityId: this.notification.data.user_diary_activity_id,
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
#notification .notification-card {
  padding: 19px;
  background: #ffffff;
  -webkit-box-shadow: 0px 2px 7px rgba(0, 0, 0, 0.25);
  box-shadow: 0px 2px 7px rgba(0, 0, 0, 0.25);
  border-radius: 4px;
  margin-bottom: 30px;
}

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
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    width: 50%;
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
  max-height: 100px;
  border-radius: 50%;
}

#notification .notification-icon-box {
  height: 2rem;
  width: 2rem;
  position: absolute;
  right: 0;
  bottom: 0;
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