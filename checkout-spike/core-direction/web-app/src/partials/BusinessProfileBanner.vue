<template>
  <div class="business-profile-banner-box">
    <img :src="companyBanner" alt="">
  </div>
</template>

<script>
export default {
    name: "BusinessProfileBanner",
    props: {
        companyBanner: {
            type: String,
            default: "",
        },
    }
};
</script>

<style scoped>

</style>