<template>
  <section id="activity-listing-days-filter">
    <div class="custom-container">
      <swiper-component :data="comingDays">
        <template #default="{slideData}">
          <button
            :class="{ active: currentDate === slideData.date }"
            class="btn-day-filter"
            @click="selectDate(slideData.date)"
          >
            <p class="day">
              {{ slideData.day }}
            </p>
            <p class="date">
              {{ slideData.date }}
            </p>
          </button>
        </template>
      </swiper-component>
    </div>
  </section>
</template>

<script>

import SwiperComponent from "@/components/swiper-component";

export default {
  name: "DateFilterActivityListing",
  components: {
    SwiperComponent,
  },
  props: {
    comingDays: {
      type: Array,
      required: true
    },
    currentDate: {
      type: String,
      default: ""
    }
  },

  methods: {
    selectDate(selectDate) {
      this.$emit("dateFilter", selectDate);
    }
  }
};
</script>

<style scoped>
#activity-listing-days-filter {
  margin-top: -60px !important;
}
</style>