<template>
  <div class="payment-type-filter-box ml-4">
    <div class="custom-container">
      <div class="flex items-center justify-between">
        <div class="title-box">
          <h4 class="activity-listing-title capitalize">
            Payment Type
          </h4>
        </div>
      </div>
    </div>
    <div class="section-body">
      <div class="custom-container">
        <div class="content-container">
          <div
            class="swiper-container mass-events-swiper-container swiper-initialized swiper-horizontal swiper-pointer-events swiper-free-mode swiper-backface-hidden"
          >
            <div id="swiper-wrapper" aria-live="polite" class="swiper-wrapper">
              <filter-button-booking v-for="(filter, index) in filters"
                                     :key="index"
                                     :filter="filter"
                                     @click="PaymentTypeFilter(filter.type)"
              />
            </div>
            <span aria-atomic="true" aria-live="assertive" class="swiper-notification" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import FilterButtonBooking from "@/filter-button-booking";

export default {
  name: "PaymentTypeFilter",
  components: {
    FilterButtonBooking,
  },

  data() {
    return {
      isPaymentFree: null,
      filterClickTracker: {paid: false, free: false},
      filters: [
        {
          type: "paid",
          image: "/assets/images/paid.png",
          label: "Paid",
        },
        {
          type: "free",
          image: "/assets/images/free.png",
          label: "Free",
        },
      ],
    };
  },
  methods: {


    PaymentTypeFilter(type) {
      this.filterClickTracker[type] = !this.filterClickTracker[type];

      if (this.filterClickTracker["paid"] && this.filterClickTracker["free"]) {
        this.isPaymentFree = null;
      } else if (this.filterClickTracker["paid"]) {
        this.isPaymentFree = false;
      } else if (this.filterClickTracker["free"]) {
        this.isPaymentFree = true;
      } else {
        this.isPaymentFree = null;
      }
      this.$emit("clicked", this.isPaymentFree);
    }
  },
};
</script>

<style scoped>
.payment-type-filter-box {
  width: 100%;
}

.payment-type-filter-box .swiper-wrapper {
  flex-wrap: wrap !important;
}

.payment-type-filter-box .section-body .swiper-container {
  margin-right: 0;
  width: 100%;
}

.payment-type-filter-box .btn-filter {
  margin-bottom: 15px;
}
</style>
