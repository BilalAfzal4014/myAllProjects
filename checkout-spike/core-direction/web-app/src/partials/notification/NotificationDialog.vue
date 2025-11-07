<template>
  <div
    :class="{
      'notification-window': true,
      'hide-notification': !showNotification
    }"
  >
    <div class="notification-header flex items-center justify-between">
      <p class="notification-header-title">
        Notification Center
      </p>
      <button>New Updates ({{ unreadNotificationCount }})</button>
    </div>
    <div class="notification-body">
      <ul class="notification-list">
        <NotificationItem
          v-for="(notificationDetail, index) in notifications"
          :key="`notification-${index}`"
          :notification-detail="notificationDetail"
        />
      </ul>
    </div>
    <div class="see-all">
      <router-link class="btn-see-all" to="/notification-listing">
        See All
      </router-link>
    </div>
  </div>
</template>

<script>
import NotificationItem from "@/partials/NotificationItem";

export default {
    name: "NotificationDialog",
    components: {NotificationItem},
    props: {
        showNotification: {
            type: Boolean,
            default: false,
        },
        notifications: {
            type: Array,
            default: () => [],
        },
        unreadNotificationCount: {
            type: Number,
            default: 0,
        },
    },
};
</script>

<style scoped>
.see-all {
  text-align: center;
  padding: 0.7rem 0;
}

.btn-see-all {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 500;
  letter-spacing: 0;
  text-align: center;
  color: #690fad;
}

@media screen and (max-width: 767px) {
  #app-header .notification-window {
    width: 100%;
    max-width: 300px;
  }
}
</style>