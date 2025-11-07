<template>
  <!-- Slides -->
  <div class="swiper-slide">
    <a href="#">
      <div class="our-partners-logo-box relative mr-4 mb-4">
        <img :src="path" alt="{{title}}"
             class="our-partners-logo"
        >
      </div>
    </a>
  </div>
</template>

<script>
export default {
    props: {
        path: {
            type: String,
            default: "",
        },
        title: {
            type: String,
            default: "",
        },
    },
    name: "PartnerImageComponent"
};
</script>

<style scoped>

</style>