<template>
  <div>
    <p class="booking-activity-time">
      <strong>Sessions</strong> | {{ subscriptionStatusOrUsage }}
    </p>
    <ul class="booking-activity-info-list">
      <li :class="status ==='active' ? 'status-active' : 'expire-soon'"
          class="booking-activity-info-item flex items-center flex-wrap"
      >
        <span class="booking-activity-info-item-icon-box flex justify-center">
          <svg fill="none" height="10" viewBox="0 0 10 10" width="10" xmlns="http://www.w3.org/2000/svg">
            <circle cx="5" cy="5.00049" fill="#FFAB07" r="5" />
          </svg>
        </span>
        <strong>
          {{ status.toUpperCase() }} {{ status.toUpperCase() === 'CANCELLING' ? '-' : '' }} {{ cancelDate }}
        </strong>
        <span class="subscription-tag">{{ packageType }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "PackageInfo",
  props: {
    consumed: {
      type: Number,
      default: 0,
    },
    cardType: {
      type: String,
      default: "member"
    },
    visits: {
      type: Number,
      default: 0,
    },
    status: {
      type: String,
      default: "",
    },
    package_name: {
      type: String,
      default: ""
    },
    endDate: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      CARD_TYPES: {
        SUBSCRIPTION: "subscription",
      },
      STATUS: {
        CANCELLING: "CANCELLING",
      },
    };
  },

  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = ("0" + (date.getMonth() + 1)).slice(-2);
      const day = ("0" + date.getDate()).slice(-2);
      return `${year}/${month}/${day}`;
    },
    shouldShowCancelDate(isSubscription, isCancelling) {
      return isSubscription && isCancelling;
    }
  },

  computed: {
    subscriptionStatusOrUsage() {
      if (this.cardType === "subscription") {
        return "Unlimited";
      }
      return `${this.consumed} / ${this.visits}`;
    },
    packageType() {
      if (this.cardType === "subscription") {
        return "MEMBERSHIP";
      }
      return "Packages";
    },
    cancelDate() {
      const isSubscription = this.cardType === this.CARD_TYPES.SUBSCRIPTION;
      const isCancelling = this.status.toUpperCase() === this.STATUS.CANCELLING;

      if (this.shouldShowCancelDate(isSubscription, isCancelling)) {
        return this.formatDate(this.endDate);
      }
      return null;
    }

  }
};
</script>
