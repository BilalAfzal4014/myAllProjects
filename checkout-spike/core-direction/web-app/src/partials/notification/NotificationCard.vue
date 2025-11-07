<template>
  <div
    :class="`notification-card ${
      notification.notification_data.read ? '' : 'unread-notification'
    }`"
    @click="
      !notification.notification_data.read &&
        updateNotificationStatus(notification.id)
    "
  >
    <FollowRequest
      v-if="
        notification.notification_data.notification_type === following ||
          notification.notification_data.notification_type === followRequest
      "
      :notification="notification"
    />
    <ActivityInvite
      v-else-if="isActivity"
      :notification="notification"
    />
  </div>
</template>

<script>
import {NOTIFICATION_TYPE} from "@/common/constants/constants";
import FollowRequest from "@/partials/notification/FollowRequest";
import ActivityInvite from "@/partials/notification/ActivityInvite";
import {updateNotificationStatus} from "@/apiManager/notification";

export default {
  name: "NotificationCard",
  components: {ActivityInvite, FollowRequest},
  data() {
    return {
      followRequest: NOTIFICATION_TYPE.FOLLOW_REQUEST,
      following: NOTIFICATION_TYPE.FOLLOWING,
      activityInvite: NOTIFICATION_TYPE.ACTIVITY_INVITE,
      activityRequest: NOTIFICATION_TYPE.ACTIVITY_INVITE,
      acceptActivityRequest: NOTIFICATION_TYPE.ACTIVITY_ACCEPTED,
      rejectedActivityRequest: NOTIFICATION_TYPE.ACTIVITY_INVITE_REJECTED,
      leaveActivity: NOTIFICATION_TYPE.LEAVE_ACTIVITY,
      removedActivity: NOTIFICATION_TYPE.ACTIVITY_REMOVED
    };
  },
  props: {
    notification: {
      type: Object,
      default: null,
    },
  },
  computed: {
    isActivity() {
      return this.notification.notification_data.notification_type === this.activityRequest || this.notification.notification_data.notification_type === this.acceptActivityRequest || this.notification.notification_data.notification_type === this.rejectedActivityRequest || this.notification.notification_data.notification_type === this.leaveActivity || this.notificationData.notification_type === this.removedActivity;
    },
  },
  methods: {
    getUpdateNotificationPayload(notificationId) {
      return {notificationId: notificationId};
    },
    updateNotificationStatus(notificationId) {
      updateNotificationStatus(
        this.getUpdateNotificationPayload(notificationId)
      )
        .then((response) => {
          this.notification.notification_data.read = true;
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

#notification .unread-notification {
  background-color: #f5f5f5;
}
</style>