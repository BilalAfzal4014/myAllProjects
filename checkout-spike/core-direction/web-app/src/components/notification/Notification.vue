<template>
  <div v-click-outside="setCloseNotificationPopup" class="notification_container">
    <div class="account-controls-item mx-1.5" @click.stop="$emit('onToggleNotification')">
      <div class="account-controls-link rounded-full">
        <BellIcon />
      </div>
      <div class="account-controls-counter">
        <span>{{ notification.unreadCount }}</span>
      </div>
    </div>
    <NotificationDialog
      :notifications="notificationsList"
      :show-notification="isNotificationShown"
      :unread-notification-count="notification.unreadCount"
    />
  </div>
</template>

<script>
import BellIcon from "../../svgs/bell-icon";
import NotificationDialog from "../../partials/notification/NotificationDialog";
import {getNotificationList} from "@/apiManager/notification";
import * as toastr from "toastr";
import {NOTIFICATION_STATUS} from "@/common/constants/constants";

export default {
    name: "Notification",
    components: {NotificationDialog, BellIcon},
    data() {
        return {
            notification: {},
            notificationsList: [],
            objectKeys: [],
            count: 0,
            userProfile: JSON.parse(localStorage.getItem("userProfile"))
        };
    },
    props: {
        isNotificationShown: {
            type: Boolean,
            required: true
        }

    },
    mounted() {
        this.getNotificationList();
    },
    methods: {
        setCloseNotificationPopup() {
            this.$emit("onHideNotification");
        },
        setNotificationsKeys(notificationObject) {
            return [...new Set(Object.keys(notificationObject))];
        },
        getNotificationListingPayload() {
            return {
                listType: NOTIFICATION_STATUS.ALL,
                offset: 0,
                limit: 10,
            };
        },
        getNotificationList(type) {
            getNotificationList(this.getNotificationListingPayload(type))
                .then((response) => {
                    this.objectKeys = this.setNotificationsKeys(
                        response.data.notifications
                    );
                    for (let date of this.objectKeys) {
                        this.notificationsList = [
                            ...this.notificationsList,
                            ...response.data.notifications[`${date}`],
                        ];
                    }
                    this.notification = response.data;
                })
                .catch((error) => {
                    toastr.error(error[0].response.data.errors[0].error);
                });
        },
    },
};
</script>

<style scoped>
@media (min-width: 768px) {
  .notification_container {
    position: relative;
  }
}

.account-controls-item {
  position: relative;
  width: 36px;
  height: 36px;
}

.account-controls-link {
  cursor: pointer;
  padding: 6px;
  width: 36px;
  height: 36px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #ffffff;
  box-shadow: 0 0 4px 0 #00000040;
}

.account-controls-item .account-controls-link svg,
path {
  fill: #000000;
}

.account-controls-counter {
  font-size: 10px;
  font-weight: 500;
  line-height: 9.38px;
  width: 14px;
  height: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 30px;
  position: absolute;
  top: -1px;
  right: -1px;
  background-color: #690fad;
  color: #ffffff;
  font-family: 'Montserrat', sans-serif;
}
</style>