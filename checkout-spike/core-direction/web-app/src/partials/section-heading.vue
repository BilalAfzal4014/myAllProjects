<template>
  <div class="custom-container">
    <div class="section-header flex items-center justify-between">
      <div class="title-box">
        <h4 class="section-title capitalize">
          {{ name }}
        </h4>
      </div>
      <div class="see-all-btn-box">
        <router-link :to="url" class="capitalize text-vibrantDark btn-see-all">
          {{ title }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
    name: "SectionHeadingComponent",
    props: {
        name: {
            type: String,
            required: true,
            default: ""

        },
        title: {
            type: String,
            required: true,
            default: "",
        },
        url: {
            type: String,
            required: false,
            default: "",
        }
    },
};
</script>

<style scoped>

</style>