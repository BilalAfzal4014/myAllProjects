<template>
  <section id="notification">
    <div class="custom-container">
      <div
        class="notification-header flex items-center justify-between flex-wrap"
      >
        <h3 class="notification-title">
          Notification Center
        </h3>
        <p class="notification-update">
          New Updates ({{ notifications.unreadCount }})
        </p>
      </div>
      <div
        v-for="(notification, index) in notificationsList"
        :key="`notification-card ${index}`"
        class="notification-body"
      >
        <div class="section-divider">
          <p class="notification-date">
            {{ getFormattedDate(index) }}
          </p>
        </div>
        <NotificationCard
          v-for="notificationDetails in notification"
          :key="`notification-${index}-${notificationDetails.id}`"
          :notification="notificationDetails"
        />
      </div>
    </div>
    <Pagination :current-page="currentPage" :pagination-count="paginationCount" @setCurrentPageValue="setCurrentPage" />
  </section>
</template>

<script>
import {NOTIFICATION_LIMIT, NOTIFICATION_STATUS} from "@/common/constants/constants";
import { getNotificationList } from "@/apiManager/notification";
import * as toastr from "toastr";
import { monthNames } from "@/dateConstant";
import NotificationCard from "@/partials/notification/NotificationCard";
import {createArrayWithRange, updateMetaInformation} from "@/utils";
import Pagination from "@/partials/wallet/Pagination";

export default {
  name: "Notification",
  components: {Pagination, NotificationCard },
  data() {
    return {
      showNotification: false,
      notifications: {},
      notificationsList: [],
      objectKeys: [],
      offset: 0,
      limit: NOTIFICATION_LIMIT,
      currentPage: 1,
      paginationCount: []
    };
  },
  mounted() {
    this.getNotificationListing(NOTIFICATION_STATUS.ALL);
    updateMetaInformation("Notifications | Core Direction", "", "View your latest Core Direction invites & notifications", "Notifications | Core Direction", "View your latest Core Direction invites & notifications", "https://cdn.coredirection.com/cdn-cgi/image/quality=auto,width=1200,height=630,crop_gravity=center,format=auto/lp-content/assets/img/graph-image/coredirection.webp", "https://my.coredirection.com/notification-listing");
  },
  watch: {
    async currentPage() {
      this.calculateOffset();
      await this.getNotificationWithOffset();
    }
  },
  methods: {
    setCurrentPage(pageNumber) {
      this.currentPage = pageNumber;
    },
    calculateOffset() {
      this.offset = (this.limit * this.currentPage) - this.limit;
    },
    getFormattedDate(dateString) {
      const today = new Date();
      const todayDate = today.getDate();
      const todayMonth = today.getMonth();
      const todayYear = today.getFullYear();
      const notificationsDate = new Date(dateString);
      const formattedDate = notificationsDate.getDate();
      const formattedMonth = notificationsDate.getMonth();
      const formattedYear = notificationsDate.getFullYear();
      const isSameYearMonth =
        formattedYear === todayYear && formattedMonth === todayMonth;
      const isTodayDate = formattedDate === todayDate;
      const isYesterdayDate = formattedDate + 1 === todayDate;
      if (isSameYearMonth && isTodayDate) return "Today";
      if (isSameYearMonth && isYesterdayDate) return "Yesterday";
      return `${monthNames[formattedMonth]} ${formattedDate}, ${formattedYear}`;
    },
    setAllNotificationCount(data) {
      this.offset = data + this.offset;
    },
    setNotificationsKeys(notificationObject) {
      return [...new Set(Object.keys(notificationObject))];
    },
    async getNotificationWithOffset() {
      try {
        const response = await getNotificationList(
          this.getNotificationListingPayload(
            NOTIFICATION_STATUS.ALL,
            this.offset
          )
        );
        this.notificationsList = response.data.notifications;
      } catch (e) {
        toastr.error(e);
      }
    },
    getNotificationListingPayload(type, offset = 0) {
      return {
        listType: type,
        offset: offset,
        limit: this.limit,
      };
    },
    getNotificationListing(type) {
      getNotificationList(this.getNotificationListingPayload(type))
        .then((response) => {
          this.notifications = response.data;
          this.notificationsList = response.data.notifications;
          this.paginationCount = createArrayWithRange(1, Math.ceil(response.data.totalNotificationCount/this.limit));
          this.objectKeys = this.setNotificationsKeys(
            response.data.notifications
          );
          for (let date of this.objectKeys) {
            this.setAllNotificationCount(
              response.data.notifications[`${date}`].length
            );
          }
        })
        .catch((error) => {
          toastr.error(error[0].response.data.errors[0].error);
        });
    },
  },
};
</script>

<style scoped>
#notification {
  padding-top: 60px;
  padding-bottom: 60px;
}
#notification .notification-header {
  padding-bottom: 30px;
  border-bottom: 1px solid #caa8f5;
}
@media (max-width: 767px) {
  #notification .notification-header {
    padding-bottom: 15px;
  }
}
#notification .notification-title {
  font-family: "Montserrat", sans-serif;
  font-size: 24px;
  font-weight: 500;
  line-height: 29px;
  letter-spacing: 0em;
  text-align: left;
}
@media (max-width: 767px) {
  #notification .notification-title {
    font-size: 18px;
  }
}
@media (max-width: 389px) {
  #notification .notification-title {
    font-size: 14px;
    font-weight: 700;
  }
}
#notification .notification-update {
  font-family: "Montserrat", sans-serif;
  font-size: 16px;
  font-weight: 500;
  line-height: 20px;
  letter-spacing: 0em;
  text-align: right;
  color: #690fad;
}
@media (max-width: 767px) {
  #notification .notification-update {
    font-size: 12px;
  }
}
#notification .notification-body {
  padding-top: 20px;
  padding-bottom: 20px;
}
#notification .section-divider {
  position: relative;
  width: 100%;
  max-width: 1060px;
  margin-left: auto;
  margin-right: auto;
  margin-bottom: 20px;
}
#notification .section-divider::before {
  position: absolute;
  content: "";
  left: 0;
  right: 0;
  background: #06070e73;
  width: 100%;
  height: 1px;
  top: 10px;
}
#notification .notification-date {
  font-family: "Montserrat", sans-serif;
  font-size: 14px;
  font-weight: 400;
  line-height: 20px;
  letter-spacing: 0px;
  text-align: center;
  position: relative;
  background-color: #ffffff;
  max-width: 160px;
  margin-left: auto;
  margin-right: auto;
}
</style>