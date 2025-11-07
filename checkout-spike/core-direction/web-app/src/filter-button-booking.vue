<template>
  <div aria-label="button" class="swiper-slide" role="group">
    <button @click="$emit('click')">
      <div class="btn-filter">
        <div class="btn-filter-icon-box">
          <img :src="filter.image" :alt="`${filter.label} icon`">
        </div>
        <p class="btn-filter-content-box">
          {{ filter.label }}
        </p>
      </div>
    </button>
  </div>
</template>

<script>
export default {
  name: "FilterButtonBooking",
  props: {
    filter: {
      type: Object,
      required: true,
    },
  },
};
</script>

