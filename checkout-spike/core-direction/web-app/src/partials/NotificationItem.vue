<template>
  <li
    :class="notificationClass"
    @click="handleNotificationClick"
  >
    <FollowRequestNotification
      v-if="isFollowRelated"
      :notification-detail="notificationDetail"
    />
    <ActivityInviteNotification
      v-if="isActivity"
      :notification-detail="notificationDetail"
    />
  </li>
</template>

<script>
import {getDurationInDays} from "@/utils";
import {updateNotificationStatus} from "@/apiManager/notification";
import * as toastr from "toastr";
import FollowRequestNotification from "@/partials/notification/FollowRequestNotification";
import ActivityInviteNotification from "@/partials/notification/ActivityInviteNotification";
import {NOTIFICATION_TYPE} from "@/common/constants/constants";

export default {
  name: "NotificationItem",
  components: {ActivityInviteNotification, FollowRequestNotification},
  data() {
    return {
      following: NOTIFICATION_TYPE.FOLLOWING,
      followRequest: NOTIFICATION_TYPE.FOLLOW_REQUEST,
      activityRequest: NOTIFICATION_TYPE.ACTIVITY_INVITE,
      acceptActivityRequest: NOTIFICATION_TYPE.ACTIVITY_ACCEPTED,
      rejectedActivityRequest: NOTIFICATION_TYPE.ACTIVITY_INVITE_REJECTED,
      leaveActivity: NOTIFICATION_TYPE.LEAVE_ACTIVITY,
      removedActivity: NOTIFICATION_TYPE.ACTIVITY_REMOVED,
      duration: getDurationInDays(this.notificationDetail.created_date),
    };
  },
  props: {
    notificationDetail: {
      type: Object,
      default: null,
    },
  },
  computed: {
    notificationData() {
      return this.notificationDetail.notification_data;
    },
    notificationClass() {
      return {
        "notification-card": true,
        "notification-card-unread": !this.notificationData.read,
      };
    },
    isFollowRelated() {
      return [this.following, this.followRequest].includes(this.notificationData.notification_type);
    },
    isActivity() {
      return this.notificationData.notification_type === this.activityRequest || this.notificationData.notification_type === this.acceptActivityRequest || this.notificationData.notification_type === this.rejectedActivityRequest || this.notificationData.notification_type === this.leaveActivity || this.notificationData.notification_type === this.removedActivity;
    },
  },
  methods: {
    handleNotificationClick() {
      if (!this.notificationData.read) {
        this.updateNotificationStatus(this.notificationDetail.id);
      }
    },
    updateNotificationStatus(notificationId) {
      const payload = {notificationId};
      updateNotificationStatus(payload)
        .then(() => {
          this.notificationData.read = true;
        })
        .catch(([{response: {data: {errors}}}]) => {
          toastr.error(errors[0].error);
        });
    },
  },
};
</script>

<style scoped>
.notification-card {
  background: #ffffff;
  -webkit-box-shadow: 0 2px 7px rgb(0 0 0 / 10%);
  box-shadow: 0 2px 7px rgb(0 0 0 / 10%);
  border-radius: 4px;
  padding: 14px 22px;
  margin-bottom: 10px;
}

.notification-card-unread {
  background-color: #f5f5f5;
}
</style>
